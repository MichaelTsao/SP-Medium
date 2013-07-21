<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$month = $argv[1];

$db = new DB();
$com = $db->getCommandInfo();

$db_conf = Config::getConfig('db_conf');
$mysql = new MySQL($db_conf);

$table_name = 'mo_' . $month;
$rs = $mysql->Query('select * from ' . $table_name . ' where client=0');
while ($row = $mysql->FetchArray($rs))
{
	foreach ($com as $c)
	{
		if (substr($row['content'], 0, strlen($c['command'])) == $c['command'])
		{
			$sql = 'update ' . $table_name . ' set client=' . $c['client'] . ', channel=' . $c['sp'] . ', op=' . $c['operator'] . ', platform=' . $c['platform'] . ' where id=' . $row['id'];
			$mysql->Query($sql);
			$sql = 'update mt_' . $month . ' set client=' . $c['client'] . ', channel=' . $c['sp'] . ', op=' . $c['operator'] . ', platform=' . $c['platform'] . ' where linkid=' . $row['linkid'];
			$mysql->Query($sql);
			break;
		}
	}
}