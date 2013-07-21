<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$type = $_REQUEST['t'];
$step = $_REQUEST['s'];

$data['type'] = $type; 
$data['step'] = $step; 

if ($type == 1)
{
	if ($step == 1)
	{
		$url = 'http://wap.bmccp.com/wgame/CBS/tb/ctwx120105.jsp';
	}
	elseif ($step == 2) 
	{
		$keys = array(
				'sid',
				'uid',
				'key',
				);
		$recv = new Recv($keys);
		$subdata = $recv->getData();		
		$url = 'http://221.179.218.152/CBS/tb/ctwxo120105.jsp?sid=' . $subdata['sid'] . '&uid=' . $subdata['uid'] . '&key=' . $subdata['key'];
		$data = array_merge($data, $subdata);
	}
}

$log_name = 'game';
$log = new Log($log_name);
$log->write($data);

header('Location: '.$url);
