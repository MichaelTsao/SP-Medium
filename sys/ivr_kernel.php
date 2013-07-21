<?
require_once dirname(__FILE__) . '/../model/autoload.php';

$gm = new Gearman('worker');
$gm->addWork('ivr_kernel', 'ivr');
while(1)
    $gm->work();

function ivr($job)
{
	$data = unserialize($job->workload());
	$gm = new Gearman('client');
	$mc = MCache::instance();
	
	$send = 0;
	$data['sp'] = 0;
	$data['client'] = 0;
	$data['operator'] = 0;
	$data['platform'] = 3;
	if (strtolower($data['carrier']) == "cmcc")
		$data['operator'] = 1;
	elseif (strtolower($data['carrier']) == "unicom")
		$data['operator'] = 2;
		
	$prov_name = Config::getConfig('prov_name');
	foreach ($prov_name as $pkey => $pname)
	{
		$prov_key[$pname] = $pkey;
	}
	if (array_key_exists($data['province'], $prov_key))
		$data['province'] = $prov_key[$data['province']];
	else 
	{
		$prov = $mc->get('pp_' . substr($data['mobile'], 0, 7));
		if ($prov)
			$data['province'] = $prov;
		else 
			$data['province'] = 'bj';
	}
	
	$default_client = Config::getConfig('default_client');
	
	$commands = $mc->get('commands');
	foreach ($commands as $com)
	{
		if ( substr(strtolower($data['callno']), 0, strlen($com['command'])) == strtolower($com['command']) && $com['platform'] == $data['platform'])
		{
			$data['sp'] = $com['sp'];
			$data['client'] = $com['client'];
			//$data['operator'] = $com['operator'];
			$data['pid'] = $com['pid'];
			$data['cid'] = $com['cid'];
			
			//调整
			//省
			$key = $data['client'] . '_' . $data['platform'] . '_' . $data['province'];
			
			$key_sent = 'sent_' . $key . '_' . date('Ymd');
			$send_all = $mc->get($key_sent);
			if ($send_all === FALSE)
			{
				$mc->set($key_sent, 0, 86400);
				$send_all = 0;
			}
			
			$got = 0;
			$adjust = new adjust();
			$key_adjust = $adjust->makeKey($key);
			$adj = $mc->get($key_adjust);
			if ($adj)
			{
				$min = Config::getConfig('adjust_min');
				$rand = rand(1, 100);
				if ($send_all > $min && $adj >= $rand)
				{
					$got = 1;
				}
			}
			
			$mc->increment($key_sent);

			//全国
			if ($got == 0 && !$adj)
			{
				$key = $data['client'] . '_' . $data['platform'] . '_cn';
				
				$key_sent = 'sent_' . $key . '_' . date('Ymd');
				$send_all = $mc->get($key_sent);
				if ($send_all === FALSE)
				{
					$mc->set($key_sent, 0, 86400);
					$send_all = 0;
				}
				
				$adjust = new adjust();
				$key_adjust = $adjust->makeKey($key);
				$adj = $mc->get($key_adjust);
				if ($adj)
				{
					$min = Config::getConfig('adjust_min');
					$rand = rand(1, 100);
					if ($send_all > $min && $adj >= $rand)
					{
						$got = 1;
					}
				}
				
				$mc->increment($key_sent);
			}
						
			
			//限量
			if ($got == 0)
			{
				$quota_key = $data['client'] . '_' . $data['platform'] . '_' . $data['province'];
				
				$key_quota = 'quota_' . $quota_key . '_' . date('Ymd');
				$send_all = $mc->get($key_quota);
				if ($send_all === FALSE)
				{
					$mc->set($key_quota, 0, 86400);
					$send_all = 0;
				}
				
				$quotas = $mc->get('quotas');
				if ($quotas && isset($quotas[$quota_key]))
				{
					$limit = $quotas[$quota_key];
					if ($send_all > $limit)
					{
						$got = 2;
					}
				}
				
				$mc->increment($key_quota);
			}
			
			
			//分发数据
			if ($got == 1)
			{
				$send = 0;
			}
			elseif ($got == 2)
			{
				$key_quota_warn = 'quota_warn_' . $quota_key . '_' . date('Ymd');
				$warn = $mc->get($key_quota_warn);
				if ($warn === FALSE)
				{
					$data['warn'] = 1;
					$gm->doBack('ivr_sender', $data);
					$mc->set($key_quota_warn, 1, 86400);
				}
				$send = 2;
			}
			else 
			{
				$gm->doBack('ivr_sender', $data);
				$send = 1;
			}
			break;
		}
	}
	
	//处理无客户归属数据
	if ($data['client'] == 0)
	{
		$data['client'] = $default_client;
		$commands = $mc->get('original_commands');
		foreach ($commands as $com)
		{
			if ( substr(strtolower($data['callno']), 0, strlen($com['command'])) == strtolower($com['command']) )
			{
				$data['sp'] = $com['sp'];
				//$data['operator'] = $com['operator'];
				
				$send = 1;
				break;
			}
		}
	}
	
	$data['send'] = $send;
	$gm->doBack('db_ivr', $data);
	
	$log = new Log('ivr_kernel');
	$log->write($data);
}
