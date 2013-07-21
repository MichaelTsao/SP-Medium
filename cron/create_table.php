<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$name = array('mt', 'mo', 'sr', 'ivr');

$db = new DB();
foreach ($name as $n){
	$db->createTable($n);
}