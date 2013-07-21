<?
require_once dirname(__FILE__) . '/../model/autoload.php';

$gm = new Gearman('worker');
$gm->addWork('sr_sender', 'sr_send');
while(1)
    $gm->work();

// send 含义： 0、不发送（扣量）   1、正常下发    2、客户URL配置错误    3、空中MO传输失败或Linkid为空
function sr_send($job)
{
	$run_time = time();
	
	$data = unserialize($job->workload());
	
	$mc = MCache::instance();
	$client = $mc->get($data['linkid']);
	$argu = '';
	
	if (!$client)
	{
		if (!empty($data['linkid']) && time() - $data['recv_time'] < 86400)
		{
			$gm = new Gearman('client');
			$gm->doBack('wait', array('sr_sender', 1, $data));
			return;
		}
		$send = 3;
	}
	else 
	{
		$urls = $mc->get('urls');
		if (isset($urls[$client]['sr']))
		{
			$url = 'http://' . $urls[$client]['sr'];
			
			$data['client'] = $client;
			$data['url'] = $url;
			$protocol = $urls[$client]['protocol'];
			$argu = '';
			if ($protocol == 0)
			{
				$argu = '<?xml version="1.0" encoding="UTF-8"?>';
				$argu .= '<message>';
				$argu .= '<linkid>' . $data['linkid'] . '</linkid>';
				$argu .= '<mobile>' . $data['mobile'] . '</mobile>';
				$argu .= '<spid>83</spid>';
				$argu .= '<status>' . $data['status'] . '</status>';
				$argu .= '<mrtime>' . date('Y-m-d H:i:s') . '</mrtime>';
				$argu .= '</message>';
			}
			elseif ($protocol == 1)
			{
				$msgid = $mc->get($data['linkid'] . '_msgid');
				$argu = '<?xml version="1.0" encoding="GBK"?>';
				$argu .= '<message>';
				$argu .= '<msgid>' . $msgid . '</msgid>';
				$argu .= '<linkid>' . $data['linkid'] . '</linkid>';
				$argu .= '<mobile>' . $data['mobile'] . '</mobile>';
				$argu .= '<statestr>' . $data['status'] . '</statestr>';
				$argu .= '<createDate>' . date('Y-m-d H:i:s') . '</createDate>';
				$argu .= '</message>';
				$url .= "?spcode=260";
			}
			
			for($i = 0; $i < 5; $i++)
			{
				$r = Curl::post($url, $argu);
				$result = Logic::getResult($r);
				if (!empty($result))
					break;
			}
			
			$data['result'] = $result;
			$send = 1;
		}
		else
		{
			if ($client == Config::getConfig('default_client'))
			{
				$send = 0;
			}
			else 
			{
				$send = 2;
			}
		}
	}
	
	$gm = new Gearman('client');
	$gm->doBack('db_sr', $data);
	
	$data['send'] = $send;
	$log = new Log('sr_client');
	$log->write($data);
	
	$log = new Log('sr_client_data');
	$log->write($argu);
	
//	$debug['all_finish'] = time() - $run_time; 
//	$log = new Log('sr_debug');
//	$log->write($debug);
}
