<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$gm = new Gearman('worker');
$gm->addWork('ivr_sender', 'ivr_send');
while(1)
    $gm->work();

function ivr_send($job)
{
	$data = unserialize($job->workload());
	
	$time_begin = time();
	
	$mc = MCache::instance();
	$urls = $mc->get('urls');
	$url = 'http://' . $urls[$data['client']]['ivr'];
	$data['url'] = $url;
	
	if (strtoupper($data['carrier']) == 'CMCC')
		$op = 1;
	elseif (strtoupper($data['carrier']) == 'UNICOM')
		$op = 2;
	else 
		$op = 3;
	
	if (isset($data['warn']) && $data['warn'] == 1)
	{
		$argu = '<?xml version="1.0" encoding="UTF-8"?>';
	    $argu .= '<message>';
		$argu .= '<warn>limit</warn>';
	    $argu .= '</message>';
	}
	else 
	{
		$argu = '<?xml version="1.0" encoding="UTF-8"?>';
	    $argu .= '<message>';
		$argu .= '<callno>' . $data['callno'] . '</callno>';
		$argu .= '<mobile>' . $data['mobile'] . '</mobile>';
		$argu .= '<calltime>' . $data['calltime'] . '</calltime>';
		$argu .= '<halttime>' . $data['halttime'] . '</halttime>';
		$argu .= '<fee>' . $data['fee'] . '</fee>';
	    $argu .= '</message>';
	}
	
	for($i = 0; $i < 5; $i++)
	{
		$r = Curl::post($url, $argu);
		$result = Logic::getResult($r);
		
		if (!empty($result))
			break;
	}
	$data['result'] = $result;
	
	$log = new Log('ivr_client');
	$log->write($data);
	$log = new Log('ivr_client_data');
	$log->write($argu);
	
	$log = new Log('ivr_debug');
	$debug = $data;
	$debug['run_time'] = time() - $time_begin;
	$log->write($debug);
}