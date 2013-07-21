<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$gm = new Gearman('worker');
$gm->addWork('apay_sender', 'apay_send');
while(1)
    $gm->work();

function apay_send($job)
{
	$data = unserialize($job->workload());
	
	$time_begin = time();
	
	$mc = MCache::instance();
	$urls = $mc->get('urls');
	$url = 'http://' . $urls[$data['client']]['apay_url'];
	$data['url'] = $url;
	
	$argu = '<?xml version="1.0" encoding="UTF-8"?>';
	$argu .= '<message>';
    $argu .= '<linkid>' . $data['transIDO'] . '</linkid>';
    $argu .= '<mobile>' . $data['phone'] . '</mobile>';
    $argu .= '<sign>' . $data['cpParam'] . '</sign>';
    $argu .= '<feeprice>' . $data['fee'] . '</feeprice>';
    $argu .= '<status>' . $data['status'] . '</status>';
    $argu .= '<time>' . date('Y-m-d H:i:s') . '</time>';
	$argu .= '</message>';

	
	for($i = 0; $i < 5; $i++)
	{
		$r = Curl::post($url, $argu);
		$result = Logic::getResult($r);
		
		if (!empty($result))
			break;
	}
	$data['result'] = $result;
	
	$log = new Log('apay_client');
	$log->write($data);
	$log = new Log('apay_client_data');
	$log->write(array('argu'=>$argu, 'result'=>$r));
	
	$log = new Log('apay_debug');
	$debug = $data;
	$debug['run_time'] = time() - $time_begin;
	$log->write($debug);
}