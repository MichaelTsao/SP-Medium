<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$gm = new Gearman('worker');
$gm->addWork('mo_sender', 'mo_send');
while(1)
    $gm->work();

function mo_send($job)
{
	$data = unserialize($job->workload());
	
	$time_begin = time();
	
	$mc = MCache::instance();
	$urls = $mc->get('urls');
	$url = 'http://' . $urls[$data['client']]['mo'];
	$data['url'] = $url;
	$protocol = $urls[$data['client']]['protocol'];
	
	if (strtoupper($data['carrier']) == 'CMCC')
		$op = 1;
	elseif (strtoupper($data['carrier']) == 'UNICOM')
		$op = 2;
	else 
		$op = 3;
	
	$kongzhong_prov = Config::getConfig('kongzhong_prov');
	$maobi_prov = Config::getConfig('maobi_prov');
		
	$argu = '';
	if (isset($data['warn']) && $data['warn'] == 1)
	{
		$argu = '<?xml version="1.0" encoding="UTF-8"?>
		        <message>
	                <warn>limit</warn>
		        </message>';
	}
	else 
	{
		if ($protocol == 0)
		{
			$argu = '<?xml version="1.0" encoding="UTF-8"?>';
			$argu .= '<message>';
		    $argu .= '<linkid>' . $data['linkid'] . '</linkid>';
		    $argu .= '<feetype>1</feetype>';
		    $argu .= '<spcode>' . $data['longcode'] . '</spcode>';
		    $argu .= '<spid>83</spid>';
		    $argu .= '<mobile>' . $data['mobile'] . '</mobile>';
		    $argu .= '<content>' . $data['content'] . '</content>';
		    $argu .= '<feeprice>' . $data['fee'] . '</feeprice>';
		    $argu .= '<operatorid>' . $op . '</operatorid>';
		    $argu .= '<provinceid>' . $maobi_prov[$data['province']] . '</provinceid>';
		    $argu .= '<motime>' . date('Y-m-d H:i:s') . '</motime>';
			$argu .= '</message>';
		}
		elseif ($protocol == 1)
		{
			$fee_type = Logic::getSKFeeType($data['platform'], $data['fee_type']);
				
			$argu = '<?xml version="1.0" encoding="GBK"?>';
			$argu .= '<message>';
		    $argu .= '<linkid>' . $data['linkid'] . '</linkid>';
		    $argu .= '<motype>1</motype>';
		    $argu .= '<spcode>260</spcode>';
		    $argu .= '<mobile>' . $data['mobile'] . '</mobile>';
		    $argu .= '<content>' . $data['content'] . '</content>';
		    $argu .= '<feeprice>' . $data['fee'] . '</feeprice>';
		    $argu .= '<feetype>' . $fee_type . '</feetype>';
		    $argu .= '<motime>' . date('Y-m-d H:i:s') . '</motime>';
			$argu .= '</message>';
		}
	}
	
	for($i = 0; $i < 5; $i++)
	{
		$r = Curl::post($url, $argu);
		$result = Logic::getResult($r);
		
		if (!empty($result))
			break;
	}
	$data['result'] = $result;
	
	$log = new Log('mo_client');
	$log->write($data);
	$log = new Log('mo_client_data');
	$log->write(array('argu'=>$argu, 'result'=>$r));
	
	$log = new Log('mo_debug');
	$debug = $data;
	$debug['run_time'] = time() - $time_begin;
	$log->write($debug);
}