<?
require_once dirname(__FILE__) . '/../model/autoload.php';

//$run_time = time();

$postdata = file_get_contents("php://input");
$data = json_decode(json_encode(simplexml_load_string($postdata)),TRUE);

$gm = new Gearman('client');
$gm->doBack('apay_kernel', $data);

$log = new Log('apay');
$log->write($data);

$log = new Log('apay_data');
$log->write($postdata);

/*
$log = new Log('apay_io_debug');
$data['run_time'] = time() - $run_time;
$log->write($data);
*/
