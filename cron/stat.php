<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$db = new DB();
$db->stat();
$db->stat(time() - 86400 * 2);
$db->stat(time() - 86400 * 3);
