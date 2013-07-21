<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

class DB
{
	protected $db;
	
	public function __construct()
	{
		$db_conf = Config::getConfig('db_conf');
		$this->db = new MySQL($db_conf);
	}
	
	protected function getTableName($type, $date = '')
	{
		if (!$date)
			$date = date('Ym');
		return strtolower($type) . '_' . $date;
	}
	
	public function matching()
	{
		$mt_name = $this->getTableName('mt');
		$sql = "select * from $mt_name where status=0";
		$rs = $this->db->Query($sql);
		while ( $row = $this->db->FetchArray($rs) )
		{
			$ids[] = "'" . $row['linkid'] . "'";
		}
		$id_str = implode(',', $ids);
		
		$sr_name = $this->getTableName('sr');
		$sql = "select status, linkid from $sr_name where linkid in ($id_str)";
		$rs = $this->db->Query($sql);
		while ( $row = $this->db->FetchArray($rs) )
		{
			if ( strtoupper($row['status']) == "DELIVRD" )
			{
				$r = 1;
			}
			else 
			{
				$r = 2;
			}
			$s = "update $mt_name set scode='" . $row['status'] . "', status=$r where linkid='" . $row['linkid'] . "'";
			$this->db->Query($s);
		}
	}
	
	public function getSP()
	{
		$this->db->Query('set names gbk');
		$sql = 'select id, name from sp';
		$rs = $this->db->Query($sql);
		while ( $row = $this->db->FetchArray($rs) )
		{
			$data[$row['id']] = $row['name'];
		}
		
		return $data;
	}
	
	public function getClient()
	{
		$this->db->Query('set names gbk');
		$sql = 'select id, name from user where priority=2';
		$rs = $this->db->Query($sql);
		while ( $row = $this->db->FetchArray($rs) )
		{
			$data[$row['id']] = $row['name'];
		}
		
		return $data;
	}
	
	protected function checkTableExist($table_name)
	{
		$sql = "show tables like '$table_name'";
		$rs = $this->db->Query($sql);
		$row = $this->db->FetchArray($rs);
		if ($row)
			return true;
		else
			return false;
	}
	
	public function countMT($stat, $argu)
	{
		$table_name = $this->getTableName('mt');
		$sql = "select fee, time, status, channel, client, prov, op, platform from $table_name";
		$rs = $this->db->Query($sql);
		while ( $row = $this->db->FetchArray($rs) )
		{
			$got = 0;
			foreach ($argu as $key => $value)
			{
				if ($row[$key] != $value && !empty($value))
					$got = 1;
			}
			if($got) continue;
			
			$date = substr($row['time'], 0, 10);
			if ($stat == 1)
			{
				$stat_field = $date;
			}
			elseif ($stat == 2)
			{
				$stat_field = $row['prov'];
			}
			
			if ($row['status'] == 1)
				$s = 1;
			else 
				$s = 0;
			$data[$stat_field][$s]++;
			$s && $money[$stat_field] += $row['fee'];
		}
		ksort($data);
		ksort($money);
		
		return array($data, $money);
	}
	
	/*
	 * 获取统计数据
	*/
	public function getStat($field, $argu)
	{
		// 初始化
		$mo = array();
		$mt = array();
		$fee = array();
		$ivr_fee = array();
		$ivr_sec = array();
		$status = array();
		
		// 组装Where条件
		$hasToday = 0;
		foreach ($argu as $key => $value)
		{
			if ($key == 'time')
			{
				if ($value < 0)
				{
					$m = $value * -1 - 1;
					$d = time() - (date('j') + 1) * 86400 - $m * 31 * 86400;
					$table_date = date('Y', $d);
					$where[] = "left(time, 7) = '" . date('Y-m', $d) . "'";
				}
				elseif ($value == 0)
				{
					$table_date = date('Y');
					$where[] = "left(time, 7) = '" . date('Y-m') . "'";
					$hasToday = 1;
				}
				else
				{
					if (strlen($value) == 7)
					{
						$table_date = substr($value, 0, 4);
						$where[] = "left(time,7) = '" . $value . "'";
					}
					else
					{
						$table_date = date('Y');
						$where[] = "time = '" . $value . "'";
						$value == date('Y-m-d') && $hasToday = 1;
					}
				}
			}
			elseif ( !empty($value) )
				$where[] = "$key = '$value'";
		}
		$where_str = 'where ' . implode(' and ', $where);
		
		// 进入数据库查询并拼装结果数据
		if ($this->checkTableExist("stat_" . $table_date))
		{
			$sql = "select send, fee, status, $field, platform from stat_$table_date $where_str";
			$rs = $this->db->Query($sql);
			while ($row = $this->db->FetchArray($rs)) 
			{
				if ($row['platform'] < 3)
				{
					$mo[$row[$field]] += $row['send'];
					if ($row['status'] == 1)
					{
						$mt[$row[$field]][$row['status']] += $row['send'];
						$fee[$row[$field]] += $row['fee'];
					}
					elseif ($row['status'] == 0) 
					{
						$mt[$row[$field]][$row['status']] += $row['send'];
					}
				}
				else 
				{
					$ivr_sec[$row[$field]] += $row['send'];
					$ivr_fee[$row[$field]] += $row['fee'];
				}
			}
		}

		//当天数据
		if ($hasToday)
		{
			// Get the Data of SMS & MMS
			$table_name = $this->getTableName('sr', date('Ym'));
			if ($this->checkTableExist($table_name))
			{
				$sql = "select status, linkid from $table_name where left(time, 10)='" . date('Y-m-d') . "'";
				$rs = $this->db->Query($sql);
				while ($row = $this->db->FetchArray($rs))
				{
					$status[$row['linkid']] = $row['status'];
				}
			}
			
			$links = array();
			$table_name = $this->getTableName('mo', date('Ym'));
			if ($this->checkTableExist($table_name))
			{
				$sql = "select time, send, channel, client, prov, op, platform, linkid, fee from $table_name where left(time, 10)='" . date('Y-m-d') . "'";
				$rs = $this->db->Query($sql);
				while ( $row = $this->db->FetchArray($rs) )
				{
					$lkey = $row['linkid'] . '_' . $row['client'];
					if (isset($links[$lkey]))
						continue;
					
					if ($row['send'] == 0)
						$row['client'] = Config::getConfig('default_client');
					
					$got = 0;
					foreach ($argu as $key => $value)
					{
						if ( $key != 'time' && ($row[$key] != $value && !empty($value)) || (empty($row[$key]) && $row['client'] != Config::getConfig('default_client')) )
							$got = 1;
					}
					if($got) continue;
					
					if ($field == 'time')
						$stat_field = substr($row['time'], 0, 10);
					else
						$stat_field = $row[$field];
						
					$mo[$stat_field]++;
					if (isset($status[$row['linkid']]))
					{
						if ($status[$row['linkid']] == 'DELIVRD' || $status[$row['linkid']] == 'deliver' || $status[$row['linkid']] == 'DeliveredToTerminal')
						{
							$s = 1;
							$fee[$stat_field] += $row['fee'];
						}
						else 
							$s = 0;
						$mt[$stat_field][$s]++;
					}
					$links[$lkey] = 1;
				}
			}
			
			// Get the Data of IVR
			$table_name = $this->getTableName('ivr', date('Ym'));
			if ($this->checkTableExist($table_name))
			{
				$sql = "show tables like '$table_name'";
				if (mysql_num_rows($this->db->Query($sql)) == 1)
				{
					$sql = "select time, send, channel, client, prov, op, fee, seconds from $table_name where left(time, 10)='" . date('Y-m-d') . "'";
					$rs = $this->db->Query($sql);
					while ( $row = $this->db->FetchArray($rs) )
					{
						if ($row['send'] == 0)
						{
							$row['client'] = Config::getConfig('default_client');
						}
						
						$got = 0;
						foreach ($argu as $key => $value)
						{
							if ($key != 'platform')
								if ( $key != 'time' && ($row[$key] != $value && !empty($value)) || (empty($row[$key]) && $row['client'] != Config::getConfig('default_client')) )
									$got = 1;
						}
						if($got) continue;
						
						if ($field == 'time')
							$stat_field = substr($row['time'], 0, 10);
						else
							$stat_field = $row[$field];
						
						$ivr_sec[$stat_field] += $row['seconds'];
						$ivr_fee[$stat_field] += $row['fee'];
					}
				}
			}
		}
		
		ksort($mo);
		
		return array($mo, $mt, $fee, $ivr_sec, $ivr_fee);
	}
	
	public function getClientStat($group, $argu)
	{
		$table_date = substr($argu['time'], 0, 4);
		foreach ($argu as $key => $value)
		{
			if ($key == 'time')
				$where[] = "left(time, 7) = '" . $value . "'";
			elseif ( !empty($value) )
				$where[] = "$key = '$value'";
		}
		$where[] = 'status = 1';
		$where_str = 'where ' . implode(' and ', $where);

		$sql = "select send, fee, platform, $group from stat_$table_date $where_str";
		$rs = $this->db->Query($sql);
		while ($row = $this->db->FetchArray($rs)) 
		{
			$mo[$row[$group]][$row['platform']] += $row['send'];
			$fee[$row[$group]][$row['platform']] += $row['fee'];
		}
		ksort($mo);
		
		return array($mo, $fee);
	}
	
	public function getClientStatDate($client)
	{
		$year = date('Y');
		if ($year > 2011)
		{
			$rs = $this->db->Query('select distinct(left(time, 7)) from stat_' . ($year - 1));
			while ($row = $this->db->FetchArray($rs))
			{
				$d[] = $row[0];
			}
		}
		$rs = $this->db->Query('select distinct(left(time, 7)) from stat_' . $year);
		while ($row = $this->db->FetchArray($rs))
		{
			$d[] = $row[0];
		}
		sort($d);
		return $d;
	}
	
	public function countMO($stat, $argu)
	{
		if ($argu['time'] == -1)
			$table_date = date('Ym', time() - (date('j') + 1) * 86400);
		else 
			$table_date = '';
		$table_name = $this->getTableName('sr', $table_date);
		$sql = "select status, linkid from $table_name";
		$rs = $this->db->Query($sql);
		while ($row = $this->db->FetchArray($rs))
		{
			$status[$row['linkid']] = $row['status'];
		}
	
		$links = array();
		$data = array();
		$mt = array();
		$money = array();
		$table_name = $this->getTableName('mo', $table_date);
		$sql = "select time, send, channel, client, prov, op, platform, linkid, fee from $table_name";
		$rs = $this->db->Query($sql);
		while ( $row = $this->db->FetchArray($rs) )
		{
			if (isset($links[$row['linkid']]))
				continue;
			
			if ($row['send'] == 0)
				$row['client'] = Config::getConfig('default_client');
			
			$got = 0;
			foreach ($argu as $key => $value)
			{
				if ($key == 'time')
				{
					if (($value != 0 && $value != -1) && substr($row['time'], 0, 10) != $value)
						$got = 1;
				}
				elseif ( ($row[$key] != $value && !empty($value)) || (empty($row[$key]) && $row['client'] != Config::getConfig('default_client')) )
					$got = 1;
			}
			if($got) continue;
			
			if ($stat == 'time')
			{
				$date = substr($row['time'], 0, 10);
				$stat_field = $date;
			}
			elseif ($stat == 'prov')
			{
				$stat_field = $row['prov'];
			}
			elseif ($stat == 'client')
			{
				$stat_field = $row['client'];
			}
			elseif ($stat == 'platform')
			{
				$stat_field = $row['platform'];
			}
			$data[$stat_field]++;
			if (isset($status[$row['linkid']]))
			{
				if ($status[$row['linkid']] == 'DELIVRD' || $status[$row['linkid']] == 'deliver')
				{
					$s = 1;
					$money[$stat_field] += $row['fee'];
				}
				else 
					$s = 0;
				$mt[$stat_field][$s]++;
			}
			$links[$row['linkid']] = 1;
		}
		ksort($data);
		
		return array($data, $mt, $money);
	}
	
	public function getCommandInfo($type = 1)
	{
		if ($type == 1) {
			$sql = "select spid, content, port, long_code, userid, unique_command, platform, operator, b.id as pid, b.commandid as cid, fee, fee_type from command a, command_assign b where a.id=b.commandid";
		}
		elseif ($type == 2) {
			$sql = "select spid, content, port, long_code, platform, operator, fee, fee_type from command";
		}
		$rs = $this->db->Query($sql);
		$i = 0;
		while ( $row = $this->db->FetchArray($rs) )
		{
			$data[$i]['sp'] = $row['spid'];
			$data[$i]['platform'] = $row['platform'];
			$data[$i]['operator'] = $row['operator'];
			$data[$i]['fee'] = $row['fee'];
			$data[$i]['fee_type'] = $row['fee_type'];
			$data[$i]['longcode'] = $row['port'] . $row['long_code'];

			if ($type == 2) {
				$data[$i]['command'] = trim($row['content']);
			}
			elseif ($type == 1) {
				$data[$i]['pid'] = $row['pid'];
				$data[$i]['cid'] = $row['cid'];
				$data[$i]['command'] = trim($row['content']) . trim($row['unique_command']);
				$data[$i]['client'] = $row['userid'];
			}
			
			$i++;
		}
		
		return $data;
	}
	
	public function getSubCommand()
	{
		$sql = "select content from command where fee_type=2";
		$rs = $this->db->Query($sql);
		while ( $row = $this->db->FetchArray($rs) )
		{
			$commands[] = $row['content'];
		}
		return $commands;
	}
	
	public function getPhoneProv()
	{
		$sql = "select prov_id, phone from phone_prov";
		$rs = $this->db->Query($sql);
		while ( $row = $this->db->FetchArray($rs) )
		{
			$data[$row['phone']] = $row['prov_id'];
		}
		
		return $data;
	}
	
	public function getQuota()
	{
		$data = array();
		$sql = "select * from quota";
		$rs = $this->db->Query($sql);
		while ( $row = $this->db->FetchArray($rs) )
		{
			$data[$row['client'] . "_" . $row['platform'] . "_" . $row['prov']] = $row['quota'];
		}
		
		return $data;
	}
		
	public function getMTContent()
	{
		$data = array();
		$sql = "select command_id, content from content";
		$rs = $this->db->Query($sql);
		while ( $row = $this->db->FetchArray($rs) )
		{
			$data[$row['command_id']][] = $row['content'];
		}
		
		return $data;
	}	
	
	public function getClientURL()
	{
		$sql = "select mo_url, sr_url, mt_url, ivr_url, sub_url, unsub_url, apay_url, id, protocol from user where priority = 2";
		$rs = $this->db->Query($sql);
		while ( $row = $this->db->FetchArray($rs) )
		{
			!empty($row['mo_url']) && $data[$row['id']]['mo'] = $row['mo_url'];
			!empty($row['sr_url']) && $data[$row['id']]['sr'] = $row['sr_url'];
			!empty($row['mt_url']) && $data[$row['id']]['mt'] = $row['mt_url'];
			!empty($row['ivr_url']) && $data[$row['id']]['ivr'] = $row['ivr_url'];
			!empty($row['sub_url']) && $data[$row['id']]['sub'] = $row['sub_url'];
			!empty($row['unsub_url']) && $data[$row['id']]['unsub'] = $row['unsub_url'];
			!empty($row['apay_url']) && $data[$row['id']]['apay_url'] = $row['apay_url'];
			$data[$row['id']]['protocol'] = $row['protocol'] == '' ? 0 : $row['protocol'];
		}
		
		return $data;
	}
	
	public function getClientMTSend()
	{
		$sql = "select send_mt, id from user where priority = 2";
		$rs = $this->db->Query($sql);
		while ( $row = $this->db->FetchArray($rs) )
		{
			$data[$row['id']] = $row['send_mt'];
		}
		
		return $data;
	}
	
	public function getClientSubMR()
	{
		$sql = "select send_sub_mr, id from user where priority = 2";
		$rs = $this->db->Query($sql);
		while ( $row = $this->db->FetchArray($rs) )
		{
			$data[$row['id']] = $row['send_sub_mr'];
		}
		
		return $data;
	}
	
	public function createTable($type)
	{
		$table_name = $this->getTableName($type);
		if ($type == 'mo')
		{
			$sql = "create table if not exists $table_name (
				id int primary key auto_increment, 
				phone varchar(50), 
				channel int, 
				client int, 
				linkid varchar(100),
				content varchar(255),
				longcode varchar(50),
				fee int,
				prov varchar(10),
				op tinyint,
				platform tinyint,
				send tinyint,
				time datetime
			)";
		}
		elseif ($type == 'mt')
		{
			$sql = "create table if not exists $table_name (
				id int primary key auto_increment, 
				phone varchar(50), 
				channel int, 
				client int, 
				linkid varchar(100),
				content varchar(255),
				longcode varchar(50),
				fee int,
				prov varchar(10),
				op tinyint,
				platform tinyint,
				scode varchar(50),
				status tinyint,
				time datetime  
			)";
		}
		elseif ($type == 'sr')
		{
			$sql = "create table if not exists $table_name (
				id int primary key auto_increment, 
				linkid varchar(100),
				status varchar(50),
				time datetime
			)";
		}
		elseif ($type == 'ivr')
		{
			$sql = "create table if not exists $table_name (
				id int primary key auto_increment, 
				phone varchar(50), 
				channel int, 
				client int, 
				callno varchar(255),
				seconds int,
				fee int,
				calltime datetime,
				halttime datetime,
				prov varchar(10),
				op tinyint,
				send tinyint,
				time datetime
			)";
		}
		elseif ($type == 'sub')
		{
			$sql = "create table if not exists $table_name (
				id int primary key auto_increment, 
				phone varchar(50),
				client int,
				content varchar(255),
				spcode varchar(255),
				fee int,
				send tinyint,
				time datetime
			)";
		}
		elseif ($type == 'apay')
		{
			$sql = "create table if not exists $table_name (
				id int primary key auto_increment, 
				phone varchar(50),
				client int,
				linkid varchar(100),
				content varchar(255),
				fee int,
				status varchar(20),
				send tinyint,
				time datetime
			)";
		}
		
		$sql && $this->db->Query($sql);
	}
	
	public function insertAPay($data, $time='')
	{
	  $time = $this->checkTime($time);
	  $this->createTable('apay');
	  $table_name = $this->getTableName('apay');
	  $sql = "insert into $table_name values(
	    '',
	    '" . $data['phone'] . "',
			'" . $data['client'] . "',
			'" . $data['linkId'] . "',
			'" . $data['sign'] . "',
			'" . $data['fee'] . "',
			'" . $data['status'] . "',
			'" . $data['sent'] . "',
			" . $time . "
		)";
			$this->db->Query('set names gbk');
			$sql && $this->db->Query($sql);
	}
	
	public function insertMO($data, $time='')
	{
		$time = $this->checkTime($time);
		$this->createTable('mo');
		$table_name = $this->getTableName('mo');
		$sql = "insert into $table_name values(
			'',
			'" . $data['mobile'] . "',
			'" . $data['sp'] . "',
			'" . $data['client'] . "',
			'" . $data['linkid'] . "',
			'" . $data['content'] . "',
			'" . $data['longcode'] . "',
			'" . $data['fee'] . "',
			'" . $data['province'] . "',
			'" . $data['operator'] . "',
			'" . $data['platform'] . "',
			'" . $data['send'] . "',
			" . $time . "
		)";
		$this->db->Query('set names gbk');
		$sql && $this->db->Query($sql);
	}

	public function insertMT($data, $time='')
	{
		$time = $this->checkTime($time);
		$this->createTable('mt');
		$table_name = $this->getTableName('mt');
		$sql = "insert into $table_name values(
			'',
			'" . $data['mobile'] . "',
			'" . $data['sp'] . "',
			'" . $data['client'] . "',
			'" . $data['linkid'] . "',
			'" . $data['content'] . "',
			'" . $data['longcode'] . "',
			'" . $data['fee'] . "',
			'" . $data['province'] . "',
			'" . $data['operator'] . "',
			'" . $data['platform'] . "',
			'',
			0,
			" . $time . "
		)";
		$this->db->Query('set names gbk');
		$sql && $this->db->Query($sql);
	}

	public function insertSR($data, $time='')
	{
		$time = $this->checkTime($time);
		$this->createTable('sr');
		$table_name = $this->getTableName('sr');
		$sql = "insert into $table_name values(
			'',
			'" . $data['linkid'] . "',
			'" . $data['status'] . "',
			" . $time . "
		)";
		$this->db->Query('set names gbk');
		$sql && $this->db->Query($sql);
	}
	
	public function insertIVR($data, $time='')
	{
		$time = $this->checkTime($time);
		$this->createTable('ivr');
		$table_name = $this->getTableName('ivr');
		$sql = "insert into $table_name values(
			'',
			'" . $data['mobile'] . "',
			'" . $data['sp'] . "',
			'" . $data['client'] . "',
			'" . $data['callno'] . "',
			'" . $data['feeseconds'] . "',
			'" . $data['fee'] . "',
			'" . $data['calltime'] . "',
			'" . $data['halttime'] . "',
			'" . $data['province'] . "',
			'" . $data['operator'] . "',
			'" . $data['send'] . "',
			" . $time . "
		)";
		$this->db->Query('set names gbk');
		$sql && $this->db->Query($sql);
	}
	
	public function insertSub($data, $time='')
	{
		$prov_name = Config::getConfig('prov_name');
		foreach ($prov_name as $pkey => $pname)
		{
			$prov_key[$pname] = $pkey;
		}
		$time = $this->checkTime($time);
		$this->createTable('sub');
		$table_name = $this->getTableName('sub');
		$sql = "insert into $table_name values(
			'',
			'" . $data['mobile'] . "',
			'" . $data['client'] . "',
			'" . $data['msgContent'] . "',
			'" . $data['msgSpCode'] . "',
			'" . $data['msgFee'] . "',
			'" . $data['send'] . "',
			" . $time . "
		)";
		$this->db->Query('set names gbk');
		$sql && $this->db->Query($sql);
	}
	
	protected function checkTime($time='')
	{
		if (empty($time))
		{
			$ftime = 'now()';
		}
		else
		{
			$ftime = "'$time'";
		}
		return $ftime;
	}
	
	public function stat($day='', $month = 0)
	{
		// Make the Time Arguments
		empty($day) && $day = time() - 86400;
		$table_year = date('Y', $day);
		$table_date = date('Ym', $day);
		$table_day = date('Y-m-d', $day);
		
		// Get the Status Report
		$table_name = $this->getTableName('sr', $table_date);
		$sql = "select status, linkid from $table_name";
		$rs = $this->db->Query($sql);
		while ($row = $this->db->FetchArray($rs))
		{
			$status[$row['linkid']] = $row['status'];
		}
		
		// Initial
		$links = array();
		$data = array();
		$money = array();
		$odata = array();
		$omoney = array();
		
		// Get the Data of SMS & MMS
		$table_name = $this->getTableName('mo', $table_date);
		$sql = "select left(time, 10) as t, send, channel, client, prov, op, platform, linkid, fee from $table_name";
		!$month && $sql .= " where left(time, 10) = '$table_day'";
		$rs = $this->db->Query($sql);
		while ( $row = $this->db->FetchArray($rs) )
		{
			$ckey = $row['linkid'] . $row['client'];
			if (isset($links[$ckey]))
				continue;
			
			$oclient = 0;
			if ($row['send'] == 0 && $row['client'] != Config::getConfig('default_client'))
			{
				$oclient = $row['client'];
				$row['client'] = Config::getConfig('default_client');
			}
			
			if (isset($status[$row['linkid']]))
			{
				if ($status[$row['linkid']] == 'DELIVRD' || $status[$row['linkid']] == 'deliver' || $status[$row['linkid']] == 'DeliveredToTerminal')
					$sta = 1;
				else 
					$sta = 0;
			}
			else 
				$sta = 2;
			
			$keys = array($row['t'], $row['channel'], $row['client'], $row['prov'], $row['op'], $row['platform'], $sta);
			$key = implode('_', $keys);
			$data[$key]++;
			$money[$key] += $row['fee'];
			
			if ($oclient)
			{
				$keys = array($row['t'], $row['channel'], $oclient, $row['prov'], $row['op'], $row['platform'], $sta);
				$key = implode('_', $keys);
				$odata[$key]++;
				$omoney[$key] += $row['fee'];
			}
			
			$links[$ckey] = 1;
		}
		
		// Get the Data of IVR
		$table_name = $this->getTableName('ivr', $table_date);
		$sql = "show tables like '$table_name'";
		if (mysql_num_rows($this->db->Query($sql)) == 1)
		{
			$sql = "select left(time, 10) as t, send, channel, client, prov, op, fee, seconds from $table_name";
			!$month && $sql .= " where left(time, 10) = '$table_day'";
			$rs = $this->db->Query($sql);
			while ( $row = $this->db->FetchArray($rs) )
			{
				$oclient = 0;
				if ($row['send'] == 0 && $row['client'] != Config::getConfig('default_client'))
				{
					$oclient = $row['client'];
					$row['client'] = Config::getConfig('default_client');
				}
				
				$keys = array($row['t'], $row['channel'], $row['client'], $row['prov'], $row['op'], 3, 1);
				$key = implode('_', $keys);
				$data[$key] += $row['seconds'];
				$money[$key] += $row['fee'];
				
				if ($oclient)
				{
					$keys = array($row['t'], $row['channel'], $oclient, $row['prov'], $row['op'], 3, 1);
					$key = implode('_', $keys);
					$odata[$key] += $row['seconds'];
					$omoney[$key] += $row['fee'];
				}
			}
		}
		
		// Create Stat Table
		$table_name = $this->getTableName('stat', $table_year);
		$sql = "create table if not exists $table_name (
			id int primary key auto_increment, 
			time date,
			channel int, 
			client int, 
			prov varchar(10),
			op tinyint,
			platform tinyint,
			status tinyint,
			send int,
			fee int,
			osend int,
			ofee int,
			insert_time datetime,
			KEY `time` (`time`),
			KEY `channel` (`channel`),
			KEY `prov` (`prov`),
			KEY `op` (`op`),
			KEY `platform` (`platform`),
			KEY `status` (`status`),
			KEY `client` (`client`)
		)";
		
		$sql && $this->db->Query($sql);
		
		// Delete Old Data
		$sql = "delete from $table_name";
		if ($month)
			$sql .= " where left(time, 7)='" . substr($table_day, 0, 7) . "'";
		else 
			$sql .= " where time='$table_day'";
		$this->db->Query($sql);
				
		// Insert Into Table
		$this->db->Query('set names gbk');
		foreach ($data as $k => $v)
		{
			$time = $this->checkTime();
			$fee = $money[$k];
			$ov = $v;
			$ofee = $fee;
			if (isset($odata[$k]))
			{
				$ov += $odata[$k];
				$ofee += $omoney[$k];
			}
			list($date, $channel, $client, $prov, $op, $platform, $stat) = explode('_', $k);
			$sql = "insert into $table_name values(
				'',
				'" . $date . "',
				'" . $channel . "',
				'" . $client . "',
				'" . $prov . "',
				'" . $op . "',
				'" . $platform . "',
				'" . $stat . "',
				'" . $v . "',
				'" . $fee . "',
				'" . $ov . "',
				'" . $ofee . "',
				" . $time . "
			)";
			$sql && $this->db->Query($sql);
		}
	}
}
