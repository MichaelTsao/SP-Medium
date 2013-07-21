<?
require_once dirname(__FILE__) . '/../model/autoload.php';

$run_time = time();

$keys = array(
		'mobile',
		'channel',
		'callno',
		'calltime',
		'province',
		);

$recv = new Recv($keys);
$data = $recv->getData();

if ($recv->getStatus())
{
//	$gm = new Gearman('client');
//	$gm->doBack('ivrin_sender', $data);
	$log_name = 'ivrin';
}
else 
{
	$log_name = 'ivrin_error';
}

$log = new Log($log_name);
$log->write($data);

//$log = new Log('ivrin_data');
//$log->write(serialize($_REQUEST));

//$log = new Log('ivrin_io_debug');
//$data['run_time'] = time() - $run_time;
//$log->write($data);
