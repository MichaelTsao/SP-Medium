<?php

class ContentController extends CX_Common_Controller
{
	public function init()
	{
		$registry = Zend_Registry::getInstance();
		
		//add CmdID Values
		$db = new CX_Common_Model('command');
		$values[0] = "È«²¿";
		foreach($db->fetch('', '', '', 0, 0, 0) as $one)
		{
			$values[$one['id']] = $one['content'] . '=>' . $one['port'];
		}
		$registry['config']->content->view->fields_values->command_id = $values;
		
		parent::init();
	}
}
