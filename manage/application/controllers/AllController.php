<?php

class AllController extends CX_Common_Controller
{
	public function init()
	{
		$this->model_name = 'Application_Model_Command';
		
		$registry = Zend_Registry::getInstance();

		//add Prov FieldsName
		for($i=1; $i<=31; $i++)
		{
			$key = 'prov' . $i;
			$registry['config']->all->view->fields_name->$key = $registry['Prov'][$i];
		}
		
		//add SpID Values
		$db = new CX_Common_Model('sp');
		foreach($db->fetch('', '', '', 0, 0, 0) as $one)
		{
			$values[$one['id']] = $one['name'];
		}
		$registry['config']->all->view->fields_values->spid = $values;
		
		//add Customers Values
		$db = new CX_Common_Model('user');
		foreach($db->fetch('', array('priority'=>2), '', 0, 0, 0) as $one)
		{
			$values[$one['id']] = $one['name'];
		}
		$registry['config']->all->view->customer = $values;
		
		$this->view->readonly = 1;
		
		parent::init();
	}
}
