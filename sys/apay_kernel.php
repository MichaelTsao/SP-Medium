<?
require_once dirname(__FILE__) . '/../model/autoload.php';

$gm = new Gearman('worker');
$gm->addWork('apay_kernel', 'apay');
while(1)
    $gm->work();

function apay($job)
{
	$data = unserialize($job->workload());
	$gm = new Gearman('client');
	$mc = MCache::instance();
	
	$phone36 = substr($data['cpParam'], 7, 7);
	if (substr($phone36, 0, 3) == 'ZZZ') {
	  $phone = '';
	}else{
	  $phone = Logic::get_num($phone36);
	}
  $data['phone'] = $phone;
  
  /*
  $commands = $mc->get('commands');
  $command_key = substr($data['sign'], 0, 6);
  foreach ($commands as $com)
  {
    $command_local = substr($com['command'], 55, 6);
    if ( substr(strtolower($command_key), 0, strlen($command_local)) == strtolower($command_local) )
    {
      $data['client'] = $com['client'];
      break;
    }
  }
  */
  
  $data['client'] = 50;
  $data['fee'] = 400;
  
  $data['sent'] = 1;
	
	$gm->doBack('db_apay', $data);
	$gm->doBack('apay_sender', $data);
	
	$log = new Log('apay_kernel');
	$log->write($data);
}
