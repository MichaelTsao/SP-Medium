<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$file = 'data/0617-SMS-MR.txt';
$date = '2012-06-17';
$type = 2;

$db_conf = Config::getConfig('db_conf');
$db = new MySQL($db_conf);
$rs = $db->Query("select id, username from user");
while ($row = $db->FetchArray($rs))
{
	$username[$row['id']] = $row['username'];
}

$kongzhong_prov = Config::getConfig('kongzhong_prov');
$maobi_prov = Config::getConfig('maobi_prov');
$prov_name = Config::getConfig('prov_name');
foreach($prov_name as $k => $v)
{
	$prov2id[$v] = $k;
}

$content = file_get_contents($file);
$file_data = explode("\n", $content);
foreach ($file_data as $line)
{
	if (trim($line) == '')
		continue;
		
	$cell = explode('[', $line);
	if ($type == 1)
	{
		$data['mobile'] = getd($cell[6]);
		$carrier = getd($cell[4]);
		$data['spcode'] = getd($cell[5]);
		$data['linkid'] = getd($cell[7]);
		$data['content'] = getd($cell[9]);
		$province = getd($cell[10]);
		$data['feeprice'] = getd($cell[13]);
		
		//$data['send'] = 1;
		$data['motime'] = $date . ' ' . substr(getd($cell[1]), 0, 8);
		
		if (strtolower($carrier) == "cmcc")
			$data['operatorid'] = 1;
		elseif (strtolower($carrier) == "unicom")
			$data['operatorid'] = 2;
		else 
			$data['operatorid'] = 3;
		
		$mc = MCache::instance();
		$commands = $mc->get('commands');
		$send_mt = $mc->get('send_mt');
		foreach ($commands as $com)
		{
			if ( substr(strtolower($data['content']), 0, strlen($com['command'])) == strtolower($com['command']) && $data['operatorid'] == $com['operator'] )
			{
				$client = $com['client'];
				//$data['platform'] = $com['platform'];
				$data['feetype'] = $com['fee_type'];
				if (!isset($data['feeprice']))
				{
					$data['feeprice'] = $com['fee'];
				}
				$client_name = $username[$client];
				$data['provinceid'] = $maobi_prov[$prov2id[$province]];
			}
		}
	}
	else 
	{
		$data['linkid'] = getd($cell[7]);
		$data['mobile'] = getd($cell[6]);
		$data['status'] = getd($cell[9]);
		$data['mrtime'] = $date . ' ' . substr(getd($cell[1]), 0, 8);
	}

	$datas = array();
	$log_file = str_replace(substr($date, 5, 2) . substr($date, 8, 3), $client_name, $file);
	foreach ($data as $key => $v)
	{
		$datas[] = $key . ":" . $v;
	}
	error_log(implode('|', $datas) . "\n", 3, $log_file);
	
}

function getd($data)
{
	$a = explode(']', $data);
	return $a[0];
}
