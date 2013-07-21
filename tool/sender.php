<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$prov_name = Config::getConfig('prov_name');
foreach ($prov_name as $key => $v){
	$pp[$v] = $key;
}

$kongzhong_prov = Config::getConfig('kongzhong_prov');
foreach ($kongzhong_prov as $key => $v){
	$kp[$v] = $key;
}

$file_name = 'data/0120XTGAMR.txt';
$type = 'sr'; //mo  sr
$channel = 'ct'; //gh  ct

if ($channel == 'gh')
{
	$ip = "211.103.244.168";
}
elseif ($channel == 'ct')
{
	$ip = "211.103.244.167";
} 

$mo_type = array(
	'longcode' => 5,
	'mobile' => 6,
	'carrier' => 4,
	'channel' => 8,
	'linkid' => 7,
	'innerid' => 12,
	'fee' => 13,
	'spid' => 4,
	'provincename' => 10,
	'content' => 9,
);

$sr_type = array(
	'mobile' => 6,
	'channel' => 5,
	'linkid' => 7,
	'fee' => 8,
	'status' => 9,
);

$fp = fopen($file_name, 'r');
while ($line = fgets($fp, 1024))
{
	$value = array();
	$ps = array();
	$line = trim($line);
	$a = explode('[', $line);
	$type_arr = $type . "_type";
	foreach ($$type_arr as $name => $num)
	{
		$value[$name] = get($a[$num]);
	}
	$type == 'mo' && $value['province'] = isset($pp[$value['provincename']]) ? $kp[$pp[$value['provincename']]] : '';
	
	$url = $ip . "/" . $type . ".php";
	foreach ($value as $k => $v)
	{
		$ps[] = $k . "=" . $v;
	}
	$param = implode('&', $ps);
	//echo $url.'?'.$param."\n";
	Curl::post($url, $param);
}
fclose($fp);

function get($s)
{
	$a = explode(']', $s);
	return $a[0];
}
