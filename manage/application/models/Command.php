<?php

class Application_Model_Command
{
	protected $_dbCommandAssign;
	protected $_dbCommandArea;
	protected $_dbCommand;
	public $paginator;

	public function __construct()
	{
		$this->_dbCommand = new CX_Common_Model('command');
		$this->_dbCommandAssign = new CX_Common_Model('command_assign');
		$this->_dbCommandArea = new CX_Common_Model('command_area');
	}
	
	public function save($data, $id=null)
	{
		unset($data['id']);
		$command_fields = $this->_dbCommand->getFields();
		
		for($i=1; $i<=31; $i++)
		{
			$prov_fields[] = 'prov' . $i;
		}
		
		foreach($data as $key => $value)
		{
			if(in_array($key, $command_fields))
			{
				$command_data[$key] = $value;
			}
			if(in_array($key, $prov_fields))
			{
				$prov_data[$key] = $value;
			}
		}
		
		if(is_null($id))
		{
			$command_id = $this->_dbCommand->save($command_data);
			$command_ids = array($command_id);
		}
		else
		{
			$this->_dbCommand->save($command_data, $id);
			
			$this->_dbCommandArea->delete($id, 'commandid');
			
			$command_ids = is_array($id) ? $id : array($id);
		}
		
		foreach($command_ids as $command_id)
		{
			foreach($prov_data as $key => $value)
			{
				if($value !== '' && !is_null($value))
				{
					$prov_id = intval(substr($key, 4));
					$one_data['commandid'] = $command_id;
					$one_data['prov'] = $prov_id;
					$one_data['description'] = $value;
					$this->_dbCommandArea->save($one_data);
				}
			}
		}
	}
	
	public function fetch($from='', $where='', $order='', $obscure=0, $page=1)
	{
		if(isset($where['assignable']))
		{
			$assignable = $where['assignable'];
			unset($where['assignable']);
		}
		
		$data = $this->_dbCommand->fetch($from, $where, $order, $obscure, $page, 0);
		
		foreach($data as $one)
		{
			$id[] = $one['id'];
		}
		
		if ($id)
		{
			$assign_data = $this->_dbCommandAssign->fetch('', array('commandid' => $id), '', 0, 0, 0);
			foreach($assign_data as $one)
			{
				$assign_hash[$one['commandid']] = 1;
			}
			
			$area_data = $this->_dbCommandArea->fetch('', array('commandid' => $id), '', 0, 0, 0);
			foreach($area_data as $one)
			{
				$area_hash[$one['commandid']][$one['prov']] = $one['description'];
			}
		}
		
		foreach($data as $key => $one)
		{
			$data[$key]['assignable'] = ($assign_hash[$one['id']] == 1 && $one['content_type'] == 2) ? 0 : 1;
			for($i=1; $i<=31; $i++)
			{
				$data[$key]['prov' . $i] = isset($area_hash[$one['id']][$i]) ? $area_hash[$one['id']][$i] : '';
			}
			if(isset($assignable) && $data[$key]['assignable'] != $assignable)
				unset($data[$key]);
		}
		
		$registry = Zend_Registry::getInstance();
		$per_page = $registry['config']->general->per_page;
		
		$this->paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data));
		$this->paginator->setItemCountPerPage($per_page);
		$this->paginator->setCurrentPageNumber($page);

		return $this->paginator->getItemsByPage($page);
	}

	public function delete($id)
	{
		$assign_data = $this->_dbCommandAssign->fetch('', array('commandid' => $id), '', 0, 0, 0);
		if(count($assign_data) > 0)
		{
			return false;
		}
		
		$this->_dbCommandArea->delete($id, 'commandid');
		$this->_dbCommand->delete($id);
	}
	
	public function assign($id, $data)
	{
		$data['commandid'] = $id;
		$data['assign_time'] = date('Y-m-d');
		$this->_dbCommandAssign->save($data);
	}
	
	public function unassign($id)
	{
		$this->_dbCommandAssign->delete($id);
	}
	
	public function getFields()
	{
		$fields = $this->_dbCommand->getFields();
		
		$fields_assign = array('assignable');
		
		for($i=1; $i<=31; $i++)
		{
			$prov[] = 'prov' . $i;
		}
		
		return array_merge($fields, $fields_assign, $prov);
	}
}
