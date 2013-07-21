<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$mc = MCache::instance();
$urls = $mc->get('urls');
$url = 'http://' . $urls[$_REQUEST['id']]['unsub'];

$argu = '<?xml version="1.0" encoding="UTF-8"?>';
$argu .= '<message>';
$argu .= '<mobile>' . $_REQUEST['mobile'] . '</mobile>';
$argu .= '<time>' . $_REQUEST['time'] . '</time>';
$argu .= '<command>' . $_REQUEST['command'] . '</command>';
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

$log = new Log('unsub_test');
$log->write($data);
$log = new Log('unsub_test_data');
$log->write($argu);
