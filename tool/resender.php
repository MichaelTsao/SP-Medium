<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$date = '20130115';
$datas = array('mo', 'sr');
//$datas = array('ivr');
$type = 2;  // 1: resend fail    2: resend not send to client    3: only into gateway, not into kernel 

foreach ($datas as $data_type)
{
	if ($type == 1)
	{
		$log = new Log($data_type . '_client');
		$data = $log->read($date);
		$gm = new Gearman('client');
		foreach ($data as $one)
		{
			if (isset($one['result']) && empty( $one['result']))
			{
				unset($one['time']);
				unset($one['ip']);
				$gm->doBack($data_type . '_sender', $one);
			}
		}
	}
	elseif ($type == 2)
	{
		$log = new Log($data_type . '_client');
		$data = $log->read($date);
		foreach ($data as $one)
		{
			$aa[$one['linkid']] = 1;
		}
		
		$log = new Log($data_type);
		$data = $log->read($date);
		$gm = new Gearman('client');
		foreach ($data as $one)
		{
			if (!isset($aa[$one['linkid']]))
			{
				unset($one['time']);
				unset($one['ip']);
				$gm->doBack($data_type . '_sender', $one);
			}
		}
	}
	elseif ($type == 3)
	{
		$log = new Log($data_type);
		$data = $log->read($date);
		foreach ($data as $one)
		{
			$gm = new Gearman('client');
			if ($data_type == "sr")
			{
				$job = $data_type . '_sender';
			}
			else 
			{
				$job = $data_type . '_kernel';
			}
			
			unset($one['time']);
			unset($one['ip']);
			$gm->doBack($job, $data);
		}
	}
}
