<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$db = new DB();
$db->stat(mktime(0,0,0,6,6,2012), 1);
