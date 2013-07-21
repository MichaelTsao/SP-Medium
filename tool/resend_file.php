<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

function get($s){
  $t = explode(']', $s);
  return $t[0];
}

$gm = new Gearman('client');

/*
$datas = file('data/0603-MO.txt', FILE_IGNORE_NEW_LINES);
foreach ($datas as $line) {
  $tmp = explode('[', $line);
  $data['mobile'] = get($tmp[6]);
  $data['carrier'] = get($tmp[4]);
  $data['longcode'] = get($tmp[5]);
  $data['channel'] = get($tmp[8]);
  $data['linkid'] = get($tmp[7]);
  $data['innerid'] = get($tmp[12]);
  $data['fee'] = get($tmp[13]);
  $data['province'] = get($tmp[10]);
  $data['spid'] = get($tmp[4]);
  $data['provincename'] = get($tmp[11]);
  $data['content'] = get($tmp[9]);
  $gm->doBack('mo_kernel', $data);
  //echo implode("|", $data)."\n";
}
*/

$datas = file('data/0603-MR.txt', FILE_IGNORE_NEW_LINES);
foreach ($datas as $line) {
  $tmp = explode('[', $line);
  $data['mobile'] = get($tmp[6]);
  $data['channel'] = get($tmp[5]);
  $data['linkid'] = get($tmp[7]);
  $data['status'] = get($tmp[9]);
  $gm->doBack('sr_sender', $data);
  //echo implode("|", $data)."\n";
}