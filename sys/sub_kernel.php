<?
require_once dirname(__FILE__) . '/../model/autoload.php';

$gm = new Gearman('worker');
$gm->addWork('sub_kernel', 'sub');
while(1)
    $gm->work();

function sub($job)
{
	$data = unserialize($job->workload());
	$gm = new Gearman('client');
	
	$data['sp'] = 0;
	$data['client'] = 0;
	$data['platform'] = 0;
	$data['operator'] = 0;
	$data['send'] = 0;
	
	$default_client = Config::getConfig('default_client');
	
	$mc = MCache::instance();
	$send_sub_mr = $mc->get('send_sub_mr');
	$commands = $mc->get('commands');
	foreach ($commands as $com)
	{
		if ( substr(strtolower($data['msgContent']), 0, strlen($com['command'])) == strtolower($com['command']) )
		{
			$data['sp'] = $com['sp'];
			$data['client'] = $com['client'];
			$data['platform'] = $com['platform'];
			$data['pid'] = $com['pid'];
			$data['cid'] = $com['cid'];
			
			$data['send'] = 1;
			$gm->doBack('sub_sender', $data);
			
			if ($send_sub_mr[$data['client']])
			{
				$sr_data = array(
				                'mobile' => $data['mobile'],
				                'channel' => '',
				                'linkid' => $data['msgLinkid'],
				                'fee' => $data['msgFee'],
				                'status' => 'DELIVRD',
								'recv_time' => time(),
				                );
				$mc->set($data['msgLinkid'], $data['client'], 72*3600);
				$gm->doBack('sr_sender', $sr_data);
			}
		}
	}			

	$gm->doBack('db_sub', $data);
	
	$log = new Log('sub_kernel');
	$log->write($data);
}
