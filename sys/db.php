<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$gm = new Gearman('worker');
$gm->addWork('db_mo', 'mo');
$gm->addWork('db_mt', 'mt');
$gm->addWork('db_sr', 'sr');
$gm->addWork('db_ivr', 'ivr');
$gm->addWork('db_sub', 'sub');
$gm->addWork('db_apay', 'apay');
while(1)
    $gm->work();

function apay($job)
{
  $data = unserialize($job->workload());

  $db = new DB();
  $db->insertAPay($data);
}

function mo($job)
{
	$data = unserialize($job->workload());
	if (!isset($data['sp'])) return;
	
	$db = new DB();
	$db->insertMO($data);
}

function mt($job)
{
	$data = unserialize($job->workload());
	$data['content'] = $data['mt'];
	if (!isset($data['sp'])) return;
	
	$db = new DB();
	$db->insertMT($data);
}

function sr($job)
{
	$data = unserialize($job->workload());
	
	$db = new DB();
	$db->insertSR($data);
}

function ivr($job)
{
	$data = unserialize($job->workload());
	
	$db = new DB();
	$db->insertIVR($data);
}

function sub($job)
{
	$data = unserialize($job->workload());
	
	$db = new DB();
	$db->insertSub($data);
}
