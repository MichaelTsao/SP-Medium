<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$gm = new Gearman('worker');
$gm->addWork('wait', 'waitor');
while(1)
    $gm->work();

function waitor($job)
{
	list($action, $type, $data) = unserialize($job->workload());
	
	$still = 0;
	if ($type == 1)
	{
		$mc = MCache::instance();
		$client = $mc->get($data['linkid']);
		if (!$client && !empty($data['linkid']) && time() - $data['recv_time'] < 86400)
		{
			$still = 1;
		}
		else 
		{
			$still = 0;
		}
	}
	
	if ($still)
	{
		sleep(1);
		$gm = new Gearman('client');
		$gm->doBack('wait', array($action, $type, $data));
		
		//$log = new Log('wait');
		//$log->write($data);
	}
	else 
	{
		$gm = new Gearman('client');
		$gm->doBack($action, $data);
	}
}
