<?php
class Log
{
	protected $name;
	
	public function __construct($name)
	{
		$this->name = $name;
	}
	
	protected function makeName($date='') 
	{
		$path = dirname(__FILE__) . '/../log/';
		empty($date) && $date = date('Ymd');
		$log_file = $path . $this->name . '_' . $date . '.log';
		return $log_file;
	}
	
	public function write($data, $date='')
	{
		$log_add['time'] = date('Y-m-d H:i:s');
		isset($_SERVER['REMOTE_ADDR']) && $log_add['ip'] = $_SERVER['REMOTE_ADDR'];
		
		!is_array($data) && $data = array('data' => $data);
		
		$data = array_merge($log_add, $data);
		
		foreach ($data as $key => $value)
		{
			$log_data[] = $key . ':' . $value;
		}
		$log_content = implode('|', $log_data) . "\n";
		
		$log_file = $this->makeName($date);
		error_log($log_content, 3, $log_file);
	}
	
	public function read($date='')
	{
		$log_file = $this->makeName($date);
		$data = array();
		$fp = @fopen($log_file, 'r');
		if ($fp)
		{
			while ( $buffer = fgets($fp, 4096) )
			{
				$data[] = $this->parseData($buffer);
			}
		}
		fclose($fp);
		return $data;
	}
	
	protected function parseData($buffer)
	{
		$data = explode('|', $buffer);
		foreach ($data as $one)
		{
			list($key, $value) = explode(':', $one, 2);
			$d[$key] = trim($value);
		}
		return $d;
	}
}