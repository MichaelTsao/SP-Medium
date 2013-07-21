<?php
require_once dirname ( __FILE__ ) . '/../model/autoload.php';

$date = date ( 'Y-m-d', time () - 86400 );
$db = new DB ();
$commands = $db->getSubCommand ();

// SMS退订
foreach ( $commands as $command ) {
	$file_name = strtoupper ( $command ) . "_unsbuscribe.$date.txt";
	doit ( $file_name, $command );
}

// MMS 退订
doit ( "mmsunsbuscribe.$date.txt", "mms" );

/******************************************************************/

// 从FTP获得数据
function doit($file_name, $command) {
	$sub_ftp = Config::getConfig ( 'sub_ftp' );
	
	$url = sprintf ( "ftp://%s:%s@%s/%s", $sub_ftp ['name'], $sub_ftp ['password'], $sub_ftp ['host'], $file_name );
	$content = Curl::post ( $url );
	if (! empty ( $content )) {
		$lines = explode ( "\n", $content );
		foreach ( $lines as $line ) {
			if (! empty ( $line )) {
				$datas = explode ( ',', $line );
				$data ['mobile'] = $datas [0];
				$data ['time'] = $datas [1];
				$data ['command'] = $command;
				send ( $data );
			}
		}
	}
}

// 发送给合作方
function send($data) {
	$mc = MCache::instance ();
	$commands = $mc->get ( 'commands' );
	$urls = $mc->get ( 'urls' );
	foreach ( $commands as $com ) {
		if (substr ( strtolower ( $data ['command'] ), 0, strlen ( $com ['command'] ) ) == strtolower ( $com ['command'] )) {
			$url = 'http://' . $urls [$com ['client']] ['unsub'];
			
			$argu = '<?xml version="1.0" encoding="UTF-8"?>
			        <message>
			            <mobile>' . $data ['mobile'] . '</mobile>
			            <time>' . $data ['time'] . '</time>
			            <command>' . $data ['command'] . '</command>
			        </message>';
			for($i = 0; $i < 5; $i ++) {
				$r = Curl::post ( $url, $argu );
				$result = Logic::getResult ( $r );
				
				if (! empty ( $result ))
					break;
			}
			$data ['result'] = $result;
			$data ['url'] = $url;
			break;
		}
	}
	
	$log = new Log ( 'unsub_client' );
	$log->write ( $data );
	$log = new Log ( 'unsub_client_data' );
	$log->write ( $argu );
}
