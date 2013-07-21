<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$gm = new Gearman('worker');
$gm->addWork('sub_sender', 'sub_send');
while(1)
    $gm->work();

function sub_send($job)
{
	$data = unserialize($job->workload());
	
	$mc = MCache::instance();
	$urls = $mc->get('urls');
	$url = 'http://' . $urls[$data['client']]['sub'];
	
	$data['op'] = Logic::getOperator($data['mobile'], 1);
	$maobi_prov = Config::getConfig('maobi_prov');
	$prov = $mc->get('pp_' . substr($data['mobile'], 0, 7));
	if ($prov)
		$data['province'] = $maobi_prov[$prov];
	else
		$data['province'] = $maobi_prov['bj'];
	
	$argu = '<?xml version="1.0" encoding="UTF-8"?>';
	$argu .= '<message>';
    $argu .= '<linkid>' . $data['msgLinkid'] . '</linkid>';
	$argu .= '<feetype>2</feetype>';
    $argu .= '<spcode>' . $data['msgSpCode'] . '</spcode>';
	$argu .= '<spid>83</spid>';
    $argu .= '<mobile>' . $data['mobile'] . '</mobile>';
    $argu .= '<content>' . $data['msgContent'] . '</content>';
    $argu .= '<feeprice>' . $data['msgFee'] . '</feeprice>';
    $argu .= '<operatorid>' . $data['op'] . '</operatorid>';
    $argu .= '<provinceid>' . $data['province'] . '</provinceid>';
    $argu .= '<motime>' . date('Y-m-d H:i:s') . '</motime>';
	$argu .= '</message>';
	
	for($i = 0; $i < 5; $i++)
	{
		$r = Curl::post($url, $argu);
		$result = Logic::getResult($r);
		
		if (!empty($result))
			break;
	}
	$data['result'] = $result;
	$data['url'] = $url;
	
	$log = new Log('sub_client');
	$log->write($data);
	$log = new Log('sub_client_data');
	$log->write($argu);
}