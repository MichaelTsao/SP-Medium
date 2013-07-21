<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setAction('/index/login');
        
		$this->addElement('text', 'username', array('required' => true));    
		$this->addElement('password', 'password', array('required' => true));    
    }


}

