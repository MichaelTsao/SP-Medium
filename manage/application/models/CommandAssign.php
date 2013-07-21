<?php

class Application_Model_CommandAssign
{
	protected $_dbCommandAssign;
	protected $_dbCommandArea;
	protected $_dbCommand;

	public function __construct()
	{
		$this->_dbCommand = new CX_Common_Model('command');
		$this->_dbCommandAssign = new CX_Common_Model('command_assign');
		$this->_dbCommandArea = new CX_Common_Model('command_area');
	}
	
	public function save($data, $id=null)
	{
		
	}
	
	public function fetch($from='', $where='', $order='', $obscure=0, $page=1)
	{
		$data = $this->_dbCommandAssign->fetch($from, $where, $order, $obscure, $page, 0);
		
		foreach($data as $one)
		{
			$id[] = $one['commandid'];
		}

		if ($id)
		{
			$command_data = $this->_dbCommand->fetch('', array('id' => $id), '', 0, 0, 0);
			foreach($command_data as $one)
			{
				$command_hash[$one['id']] = $one;
			}
			
			$area_data = $this->_dbCommandArea->fetch('', array('commandid' => $id), '', 0, 0, 0);
			foreach($area_data as $one)
			{
				$area_hash[$one['commandid']][$one['prov']] = $one['description'];
			}
		}

		foreach($data as $key => $one)
		{
			if(isset($command_hash[$one['commandid']]))
			{
				foreach($command_hash[$one['commandid']] as $cmd_key => $cmd_value)
				{
					$cmd_key != 'id' && $data[$key][$cmd_key] = $cmd_value;
				}
			}
			for($i=1; $i<=31; $i++)
			{
				$data[$key]['prov' . $i] = isset($area_hash[$one['id']][$i]) ? $area_hash[$one['id']][$i] : '';
			}
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
	}
	
	public function unassign($id)
	{
		$this->_dbCommandAssign->delete($id);
	}
	
	public function getFields()
	{
		$fields = $this->_dbCommand->getFields();
		
		$fields_assign = $this->_dbCommandAssign->getFields();
		$fields_assign = array_slice($fields_assign, 2);
		
		for($i=1; $i<=31; $i++)
		{
			$prov[] = 'prov' . $i;
		}
		
		return array_merge($fields, $fields_assign, $prov);
	}
}
