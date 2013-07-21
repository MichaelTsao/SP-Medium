<?php

class CX_Common_Model
{
	protected $_dbTable;
	public $paginator;

	public function __construct($table_name)
	{
		$this->_dbTable = new Zend_Db_Table($table_name);
	}
	
	public function save($data, $id=null)
	{
		if($data && (is_array($data) && count($data) > 0))
		{
			if(is_null($id))
			{
				$result = $this->_dbTable->insert($data);
			}
			elseif(is_array($id))
			{
				unset($data['id']);
				$result = $this->_dbTable->update($data, array('id IN ( ? )' => $id));
			}
			else
			{
				unset($data['id']);
		  		$result = $this->_dbTable->update($data, array('id = ?' => $id));
			}
		}
		return $result;
	}
	
	public function fetch($from='', $where='', $order='', $obscure=0, $page=1, $hasPagi=1)
	{
		$select = $this->_dbTable->select();
		$from && $select->from($this->_dbTable, $from);
		if($where)
			foreach($where as $key => $value)
			{
				if($obscure)
					$select->where("$key like '%$value%'");
				else
				{
					if(is_array($value))
						$select->where("$key IN ( ? )", $value);
					else
						$select->where("$key = ?", $value);
				}
			}
		$order && $select->order($order);
		
		if($hasPagi)
		{
			$registry = Zend_Registry::getInstance();
			$per_page = $registry['config']->general->per_page;

			$this->paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($select));
			$this->paginator->setItemCountPerPage($per_page);
			$this->paginator->setCurrentPageNumber($page);
			return $this->paginator->getItemsByPage($page)->toArray();
		}
		else
		{
			return $this->_dbTable->fetchAll($select)->toArray();
		}
	}
	
	public function delete($id, $key='')
	{
		if($id)
		{
			$key == '' && $key = 'id';
			
			if(is_array($id))
				return $this->_dbTable->delete(array($key . ' IN ( ? )' => $id));
			else
				return $this->_dbTable->delete(array($key . ' = ?' => $id));
		}
	}
	
	public function find($id)
	{
		if ( $id ) return $this->_dbTable->find($id);
	}
	
	public function getFields()
	{
		$a = $this->_dbTable->info('cols');
		return $a;
	}
}

