<?php
$company_name = '创通无限';
$company_mark = 'chuang';

$adjust_min = 200;

$db_conf = array
(
	'mDbHost'     => "127.0.0.1",
    'mDbUser'     => "sp",
    'mDbPassword' => "sp168",
    'mDbPort'     => "3307",
    'mDbDatabase' => "sp1"
);

$mc_conf = array(
		array("host" => "127.0.0.1", "port" => 11218, "weight" => 50)
	);
	
$gearman_conf = array(
	'hosts' => array(
		array(
			'host' => '127.0.0.1',
			'port' => 250,
			'persistent' => true,
		),
	),
);
		
$default_client = 50;
        
include 'conf_common.php';
