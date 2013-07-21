<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$prov = Config::getConfig("prov_name");
foreach ($prov as $key => $n)
{
	$p[$n] = $key;
}

$sql = "select * from phone_prov";
$db_conf = Config::getConfig('db_conf');
$db = new MySQL($db_conf);
$db->Query("set names gbk");
$rs = $db->Query($sql);
while ($row = $db->FetchArray($rs))
{
	$db->Query('update phone_prov set prov_id="' . $p[$row['prov']] . '" where id=' . $row['id']);
}


//$d = Dealer::kongLogRead('data/XFYZD06.txt');
//
//$c = 0;
//foreach ($d as $one)
//{
//	if ($one[2] == 'SEND-IVR-HALT-TO-PARTNER')
//	{
//		$c++;
//	}
//}
//echo $c;

//$stime = mktime(0,0,0, 6, 1, 2012);
//$etime = mktime(0,0,0, 7, 1, 2012);
//
//while ($stime < $etime)
//{
//	$log = new Log('mo');
//	$data = $log->read(date('Ymd', $stime));
//	foreach ($data as $one)
//	{
//		if ($one['carrier'] == 'TELECOM')
//		{
//			$all += $one['fee'];
//		}
//	}
//	
//	$stime += 86400;
//}
//
//echo $all;
//$log = new Log('sr');
//$data = $log->read();
//foreach ($data as $one)
//{
//	if (in_array($one['linkid'], $linkids))
//	{
//		$r[$one['status']]++;
//	}
//}
//
//print_r($r);