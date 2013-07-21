<?php
$company_name = '¹ú»ªº£Ì©';
$company_mark = 'guohua';

$adjust_min = 200;

$db_conf = array
(
	'mDbHost'     => "127.0.0.1",
    'mDbUser'     => "sp",
    'mDbPassword' => "sp168",
    'mDbPort'     => "3307",
    'mDbDatabase' => "sp"
);

$mc_conf = array(
		array("host" => "127.0.0.1", "port" => 11211, "weight" => 50)
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
		
$default_client = 43;
        
include 'conf_common.php';
