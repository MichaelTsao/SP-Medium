<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$db = new DB();
$mc = MCache::instance();

$command = $db->getCommandInfo();
$mc->set('commands', $command);

$original_command = $db->getCommandInfo(2);
$mc->set('original_commands', $original_command);

$urls = $db->getClientURL();
$mc->set('urls', $urls);

$send_mt = $db->getClientMTSend();
$mc->set('send_mt', $send_mt);

$send_sub_mr = $db->getClientSubMR();
$mc->set('send_sub_mr', $send_sub_mr);

$mt_content = $db->getMTContent();
$mc->set('mt_content', $mt_content);

$quota = $db->getQuota();
$mc->set('quotas', $quota);

$phone_prov = $db->getPhoneProv();
foreach ($phone_prov as $phone => $prov)
{
	$mc->set('pp_' . $phone, $prov);
}
