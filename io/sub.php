<?
require_once dirname(__FILE__) . '/../model/autoload.php';

$run_time = time();

$keys = array(
		'mobile',	//ÊÖ»úºÅ
		'carrier',
		'msgContent',
		'msgSpCode',
		'msgFee',
		'msgLinkid',
		);

$recv = new Recv($keys);
$data = $recv->getData();

if ($recv->getStatus())
{
	$gm = new Gearman('client');
	$gm->doBack('sub_kernel', $data);
	$log_name = 'sub';
}
else 
{
	$log_name = 'sub_error';
}

$log = new Log($log_name);
$log->write($data);

$log = new Log('sub_data');
$log->write(serialize($_REQUEST));

$log = new Log('sub_io_debug');
$data['run_time'] = time() - $run_time;
$log->write($data);

echo 'ok';