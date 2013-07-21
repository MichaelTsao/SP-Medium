<?php
$company_name = '';//·ÉÓîÖÇ´ï
$company_mark = 'feiyu';

$adjust_min = 200;

$db_conf = array
(
	'mDbHost'     => "127.0.0.1",
    'mDbUser'     => "root",
    'mDbPassword' => "fyzd8888",
    'mDbPort'     => "3306",
    'mDbDatabase' => "sp"
);

$mc_conf = array(
		array("host" => "127.0.0.1", "port" => 11211, "weight" => 50)
	);
	
$gearman_conf = array(
	'hosts' => array(
		array(
			'host' => '127.0.0.1',
			'port' => 4730,
			'persistent' => true,
		),
	),
);
		
$default_client = 51;
        
include 'conf_common.php';