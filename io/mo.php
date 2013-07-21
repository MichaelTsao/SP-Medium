<?
require_once dirname(__FILE__) . '/../model/autoload.php';

$run_time = time();

$keys = array(
		'mobile',
		'carrier',
		'longcode',
		'channel',
		'linkid',
		'innerid',
		'fee',
		'province',
		'spid',
		'provincename',
		'content'
		);

$recv = new Recv($keys);
$data = $recv->getData();

if ($recv->getStatus())
{
	$gm = new Gearman('client');
	$gm->doBack('mo_kernel', $data);
	$log_name = 'mo';
}
else 
{
	$log_name = 'mo_error';
}

$log = new Log($log_name);
$log->write($data);

$log = new Log('mo_data');
$log->write(serialize($_REQUEST));

$log = new Log('mo_io_debug');
$data['run_time'] = time() - $run_time;
$log->write($data);
