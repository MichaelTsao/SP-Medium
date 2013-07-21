<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$db = new MySQL(Config::getConfig('db_conf'));
$rs = $db->Query('select id, phone from mo_201201');
while ($row = $db->FetchArray($rs))
{
	$op = Logic::getOperator($row['phone'], 1);
	if ($op != '')
		$db->Query("update mo_201201 set op=$op where id=".$row['id']);
}
