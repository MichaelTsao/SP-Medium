<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
		$registry = Zend_Registry::getInstance();
	  	$this->view->appTitle = $registry['AppTitle'];
	  	$this->view->company_name = $registry['company_name'];
    }

    public function indexAction()
    {
    	$this->_helper->layout->setLayout('login');
    	$form = new Application_Form_Login();
    	$this->view->form = $form;
    }
    
    public function loginAction()
    {
    	$request = $this->getRequest();
	    if($request->isPost()) 
	    {
			$form = new Application_Form_Login();
			if ($form->isValid($request->getPost())) 
			{
				$data = $form->getValues();
				$db  = new CX_Common_Model('user');
				$rs = $db->fetch('', array('username' => $data['username'], 'password' => $data['password']));
				if(count($rs) > 0)
				{
					$session = new Zend_Session_Namespace('login');
					$session->username = $rs[0]['username'];
					$session->name = $rs[0]['name'];
					$session->priority = $rs[0]['priority'];
					$session->userid = $rs[0]['id'];
					
					if($rs[0]['priority'] == 1)
						return $this->_helper->redirector('index', 'command');
					else
						return $this->_helper->redirector('index', 'customer');
				}
			}
    	}
	  	return $this->_helper->redirector('index');
    }

    public function logoutAction()
    {
		$session = new Zend_Session_Namespace('login');
		foreach($session as $key => $value)
		{
			unset($session->$key);
		}
		  			
	  	return $this->_helper->redirector('index');
    }

}
