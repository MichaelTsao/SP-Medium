<?
class Gearman
{
	protected $gm;
	
	public function __construct($type)
	{
		if($type == 'client')
		{
			$obj = new GearmanClient();
		}
		elseif($type == 'worker')
		{
			$obj = new GearmanWorker();
		}
		else
		{
			$obj = new GearmanClient();
			$obj->setTimeout(5000);
		}
		
		$config_arr = Config::getConfig('gearman_conf');
		foreach( $config_arr['hosts'] as $host_v )
			$obj->addServer($host_v['host'],$host_v['port']);
		
		$this->gm = $obj;
	}
	
	public function getInstance()
	{
		return $this->gm;
	}
	
	public function doBack($channel, $data)
	{
		$company_mark = Config::getConfig('company_mark');
		$this->gm->doBackground($company_mark . '_' . $channel, serialize($data));
	}
	
	public function addWork($channel, $func)
	{
		$company_mark = Config::getConfig('company_mark');
		$this->gm->addFunction($company_mark . '_' . $channel, $func);
	}
	
	public function work()
	{
		$this->gm->work();
	}
}
