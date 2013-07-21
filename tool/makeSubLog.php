<?php
require_once dirname ( __FILE__ ) . '/../model/autoload.php';

$time_start = mktime(0, 0, 0, 3, 6, 2013);
$time_end = mktime(0, 0, 0, 3, 17, 2013);

$all_contents = '';
while ($time_start <= $time_end)
{
	$file_name = 'mmsunsbuscribe.' . date("Y-m-d", $time_start) . '.txt';
	
	$sub_ftp = Config::getConfig ( 'sub_ftp' );
	
	$url = sprintf ( "ftp://%s:%s@%s/%s", $sub_ftp ['name'], $sub_ftp ['password'], $sub_ftp ['host'], $file_name );
	$content = Curl::post ( $url );
	$all_contents .= $content;
	
	$time_start += 86400;
}

error_log($all_contents, 3, './data/unsub');