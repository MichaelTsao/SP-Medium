<?php

class AssignedController extends CX_Common_Controller
{
	public function init()
	{
		$this->model_name = 'Application_Model_CommandAssign';
		
		$registry = Zend_Registry::getInstance();

		//add Prov FieldsName
		for($i=1; $i<=31; $i++)
		{
			$key = 'prov' . $i;
			$registry['config']->assigned->view->fields_name->$key = $registry['Prov'][$i];
		}
		
		//add SpID Values
		$values = array();
		$db = new CX_Common_Model('sp');
		foreach($db->fetch('', '', '', 0, 0, 0) as $one)
		{
			$values[$one['id']] = $one['name'];
		}
		$registry['config']->assigned->view->fields_values->spid = $values;
		
		//add UserID Values
		$values = array();
		$db = new CX_Common_Model('user');
		foreach($db->fetch('', '', '', 0, 0, 0) as $one)
		{
			$values[$one['id']] = $one['name'];
		}
		$registry['config']->assigned->view->fields_values->userid = $values;
		
		parent::init();
	}
	
	public function unassignAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id');
		if ($id) 
		{
		    $this->db->unassign($id);
		}
		return $this->_helper->redirector('index');
	}
}
