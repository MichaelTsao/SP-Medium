<?php
$company_name = 'New';
$company_mark = 'newc';

$adjust_min = 200;

$db_conf = array
(
	'mDbHost'     => "127.0.0.1",
    'mDbUser'     => "sp2",
    'mDbPassword' => "sp268",
    'mDbPort'     => "3307",
    'mDbDatabase' => "sp2"
);

$mc_conf = array(
		array("host" => "127.0.0.1", "port" => 11228, "weight" => 50)
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

$sub_ftp = array(
	'host' => '202.108.24.158',
	'name' => 'guohua',
	'password' => 'guohuanihao',
);
		
$default_client = 1;
        
include 'conf_common.php';
