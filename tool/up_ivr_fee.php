<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$db_conf = Config::getConfig('db_conf');
$db = new MySQL($db_conf);

$date = '201205';
$table_name = "ivr_" . $date;

$sql = "select content, fee, fee_type from command where platform=3";
$rs = $db->Query($sql);
while ($row = $db->FetchArray($rs))
{
	$cmd[$row['content']] = array($row['fee'], $row['fee_type']);
}

$sql = "select seconds, fee, callno, id from $table_name";
$rs = $db->Query($sql);
while ($row = $db->FetchArray($rs))
{
	$call = $row['callno'];
	foreach (array_keys($cmd) as $key)
	{
		$len = strlen($key);
		if (substr($call, 0, $len) == $key)
		{
			$fee = $cmd[$key][0];
			$fee_type = $cmd[$key][1];
			if ($fee_type == 2)
			{
				$money = $fee;
			}
			else 
			{
				$money = $row['seconds'] / 60 * $fee;
			}
			if ($money != $row['fee'] && $row['callno'] == '116993688315')
			{
				$sql = "update $table_name set fee=$money where id=" . $row['id'];
				$db->Query($sql);
			}
		}
	}
}
