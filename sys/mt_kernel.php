<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$gm = new Gearman('worker');
$gm->addWork('mt_sender', 'mt');
while(1)
    $gm->work();

function mt($job)
{
	$data = unserialize($job->workload());
	
	$mc = MCache::instance();
	$mt_content = $mc->get('mt_content');
	
	if (isset($mt_content[$data['cid']]))
	{
		$contents = $mt_content[$data['cid']];
	}
	elseif (isset($mt_content[0]))
	{
		$contents = $mt_content[0];
	}
	else 
		$contents = false;
		
	if ($contents)
	{
		$n = array_rand($contents);
		$data['mt'] = $contents[$n];
		//$c = iconv('gbk', 'utf-8', $contents[$n]) . '@!imi!@';
		
		//Write to DB
		$gm = new Gearman('client');
		$gm->doBack('db_mt', $data);
		
		//Send to Client
		$urls = $mc->get('urls');
		if (!empty($urls[$data['client']]['mt']))
		{
			$url = 'http://' . $urls[$data['client']]['mt'];
			$protocol = $urls[$data['client']]['protocol'];
			$argu = '';
			if ($protocol == 0)
			{
				$argu = '<?xml version="1.0" encoding="UTF-8"?>
						<message>
							<linkid>' . $data['linkid'] . '</linkid>
							<mobile>' . $data['mobile'] . '</mobile>
							<spid>83</spid>
							<content>' . $data['mt'] . '</content>
							<mttime>' . date('Y-m-d H:i:s') . '</mttime>
						</message>
						';
			}
			elseif ($protocol == 1)
			{
				$fee_type = Logic::getSKFeeType($data['platform'], $data['fee_type']);
				$msgid = Logic::getMsgID();
				$mc->set($data['linkid'] . '_msgid', $msgid, 72*3600);
				$argu = '<?xml version="1.0" encoding="GBK"?>
						<message>
							<msgid>' . $msgid . '</msgid>
							<linkid>' . $data['linkid'] . '</linkid>
							<feecode>TLH</feecode>
							<feeprice>' . $data['fee'] . '</feeprice>
							<toicp>' . $data['longcode'] . '</toicp>
							<feecategory>' . $fee_type . '</feecategory>
							<mobile>' . $data['mobile'] . '</mobile>
							<content>' . $data['mt'] . '</content>
							<createDate>' . date('Y-m-d H:i:s') . '</createDate>
						</message>
						';
				$url .= "?spcode=260";
			}
			
			for($i = 0; $i < 3; $i++)
			{
				$r = Curl::post($url, $argu);
				$result = Logic::getResult($r);
				
				if (!empty($result))
					break;
			}
		}
		$data['result'] = $result;
		$data['url'] = $url;
	}
	else 
		$data['result'] = 'no content';
	
	$log = new Log('mt_client');
	$log->write($data);
	$log = new Log('mt_client_data');
	$log->write($argu);
}
