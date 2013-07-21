<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$date = '20110817';
$type = 'sr';

$db_conf = Config::getConfig('db_conf');
$db = new MySQL($db_conf);
$log = new Log($type);

$data = $log->read($date);
foreach ($data as $one)
{
	$sql = "update " . $type . "_" . substr($date, 0, 6) . " set time='" . $one['time'] . "' where linkid='" . $one['linkid'] . "' and time='0000-00-00'";
	$db->Query($sql);
}
