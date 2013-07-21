<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

class adjust
{
	public function makeKey($name)
	{
		return 'adjust_' . $name;
	}
	
	public function setAdjust()
	{
		$mc = MCache::instance();
		$db = new MySQL(Config::getConfig('db_conf'));
		$rs = $db->Query("select * from adjust");
		while ( $row = $db->FetchArray($rs) )
		{
			$k = $this->makeKey( $row['client'] . '_' . $row['platform'] . '_' . $row['prov'] );
			$mc->set($k, $row['percent']);
		}
	}
}
