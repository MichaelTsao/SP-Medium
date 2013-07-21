<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$type = $argv[1];
$date = $argv[2];

$db = new DB();
$func = 'insert' . strtoupper($type);
$file_name = $type . '_' . $date;
$table_name = substr($type, 0, 2) . "_" . substr($date, 0, 6);
$long_date = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);

$mysql = new MySQL(Config::getConfig('db_conf'));
$rs = $mysql->Query("select linkid from $table_name where left(time, 10) = '$long_date'");
while ($row = $mysql->FetchArray($rs))
{
	$links[$row['linkid']] = 1;
}

$log = new Log($type);
$data = $log->read(date('Ymd'));
foreach ($data as $one)
{
	if (!isset($links[$one['linkid']]))
	{
		$type == 'mt' && $data['content'] = $data['mt'];
		//echo serialize($data)."\n"; 
		$db->$func($data, $data['time']);
	}
}
