<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$time_start = mktime(0,0,0,4,1,2013);
$time_end = mktime(0,0,0,4,7,2013);
$datas = array('mo', 'sr');
$commands = array('mm', '8DFZYG', 'S');
$type = 3;  // 1: relog fail    2: relog not send to client    3:relog a command 

$kongzhong_prov = Config::getConfig('kongzhong_prov');
$maobi_prov = Config::getConfig('maobi_prov');
$prov_name = Config::getConfig('prov_name');

while ($time_start <= $time_end) {
	$date = date("Ymd", $time_start);
	
	if ($type == 3)
	{
		$linkids = array();
		$log = new Log('mo_client');
		$data = $log->read($date);
		foreach ($data as $one)
		{
			foreach ($commands as $com)
			{
				if (substr(strtolower($one['content']), 0, strlen($com)) == strtolower($com))
				{
					unset($one['ip']);
					unset($one['result']);
					unset($one['channel']);
					unset($one['client']);
					unset($one['url']);
					unset($one['innerid']);
					unset($one['carrier']);
					unset($one['spid']);
					unset($one['provincename']);
					unset($one['sp']);
					unset($one['platform']);
					unset($one['pid']);
					unset($one['cid']);
					$one['province'] = $prov_name[$kongzhong_prov[$one['province']]];
					$linkids[$one['linkid']] = $com;
					
					$l = new Log($com . '_mo');
					$l->write($one, $date);
				}
			}
		}
	
		$log = new Log('sr_client');
		$data = $log->read($date);
		foreach ($data as $one)
		{
			if (in_array($one['linkid'], array_keys($linkids)))
			{
				unset($one['ip']);
				unset($one['result']);
				unset($one['channel']);
				unset($one['client']);
				unset($one['url']);
				unset($one['recv_time']);
				unset($one['send']);
				
				$l = new Log($linkids[$one['linkid']] . '_sr');
				$l->write($one, $date);
			}
		}
		
		$log = new Log('sub_client');
		$data = $log->read($date);
		foreach ($data as $one)
		{
			foreach ($commands as $com)
			{
				if (substr(strtolower($one['msgContent']), 0, strlen($com)) == strtolower($com))
				{
					unset($one['result']);
					unset($one['client']);
					unset($one['url']);
					unset($one['op']);
					unset($one['send']);
					unset($one['pid']);
					unset($one['cid']);
					unset($one['carrier']);
					unset($one['sp']);
					unset($one['platform']);
					unset($one['operator']);
					$one['province'] = $prov_name[$kongzhong_prov[$one['province']]];
					
					$l = new Log($com . '_sub');
					$l->write($one, $date);
				}
			}
		}
	}
	else {
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
						unset($one['ip']);
						unset($one['result']);
						unset($one['channel']);
						unset($one['client']);
						unset($one['url']);
						if ($data_type == 'mo')
						{
							unset($one['innerid']);
							unset($one['carrier']);
							unset($one['spid']);
							unset($one['provincename']);
							unset($one['sp']);
							unset($one['platform']);
							unset($one['pid']);
							unset($one['cid']);
							$one['province'] = $prov_name[$kongzhong_prov[$one['province']]];
						}
						elseif ($data_type == 'sr')
						{
							unset($one['recv_time']);
							unset($one['send']);
						}
						
						$l = new Log($data_type . '_re');
						$l->write($one, $date);
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
						unset($one['ip']);
						unset($one['result']);
						unset($one['channel']);
						unset($one['client']);
						unset($one['url']);
						if ($data_type == 'mo')
						{
							unset($one['innerid']);
							unset($one['carrier']);
							unset($one['spid']);
							unset($one['provincename']);
							unset($one['sp']);
							unset($one['platform']);
							unset($one['pid']);
							unset($one['cid']);
							$one['province'] = $prov_name[$kongzhong_prov[$one['province']]];
						}
						elseif ($data_type == 'sr')
						{
							unset($one['recv_time']);
							unset($one['send']);
						}
										
						$l = new Log($data_type . '_re');
						$l->write($one, $date);
					}
				}
			}
		}
	}
	
	$time_start += 86400;
}

