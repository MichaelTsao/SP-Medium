<?php
class Recv
{
	protected $protocol;
	protected $data;
	protected $status;
	
	public function __construct($protocol)
	{
		$this->protocol = $protocol;
		
		foreach ($this->protocol as $key)
		{
			$this->data[$key] = trim($_REQUEST[$key]);
		}
		$this->status = true;
	}
	
	public function getData()
	{
		return $this->data;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
}