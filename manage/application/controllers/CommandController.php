<?php

class CommandController extends CX_Common_Controller
{
	public function init()
	{
		$this->model_name = 'Application_Model_Command';
		
		$registry = Zend_Registry::getInstance();

		//add Prov FieldsName
		for($i=1; $i<=31; $i++)
		{
			$key = 'prov' . $i;
			$registry['config']->command->view->fields_name->$key = $registry['Prov'][$i];
		}
		
		//add SpID Values
		$db = new CX_Common_Model('sp');
		foreach($db->fetch('', '', '', 0, 0, 0) as $one)
		{
			$values[$one['id']] = $one['name'];
		}
		$registry['config']->command->view->fields_values->spid = $values;
		
		//add Customers Values
		$db = new CX_Common_Model('user');
		foreach($db->fetch('', array('priority'=>2), '', 0, 0, 0) as $one)
		{
			$values[$one['id']] = $one['name'];
		}
		$registry['config']->command->view->customer = $values;
		
		parent::init();
	}
	
	public function assignAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id');
		if ($request->isPost()) 
		{
		  //if ($this->form->isValid($request->getPost())) 
		  //{
		    $data = $request->getPost();//$this->form->getValues();
		    $this->db->assign($id, $data);
		  //}
		}
		return $this->_helper->redirector('index');
	}
}
