<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$keys = array(
                'mobile',
                'channel',
                //'longcode',
                'linkid',
                'fee',
                'status',
                //'content',
                );

$recv = new Recv($keys);
$data = $recv->getData();

if ($recv->getStatus())
{
	$send_data = $data;
	$send_data['recv_time'] = time();
	$gm = new Gearman('client');
	$gm->doBack('sr_sender', $send_data);
		
	$log_name = 'sr';
}
else 
{
	$log_name = 'sr_error';
}

$log = new Log($log_name);
$log->write($data);

$log = new Log('sr_data');
$log->write(serialize($_REQUEST));
