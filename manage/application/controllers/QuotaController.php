<?php

class QuotaController extends CX_Common_Controller
{
	public function init()
	{
		$registry = Zend_Registry::getInstance();
		
		//add Client Values
		$db = new CX_Common_Model('user');
		foreach($db->fetch('', array('priority' => 2), '', 0, 0, 0) as $one)
		{
			$values[$one['id']] = $one['name'];
		}
		$registry['config']->quota->view->fields_values->client = $values;
		
		parent::init();
	}
}
