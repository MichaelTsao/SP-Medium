<?php

class CX_Common_Controller extends Zend_Controller_Action
{
	protected $db;
	protected $form;
	protected $name;
	protected $search_fields;
	protected $model_name;
	protected $fields_readonly;

	public function init()
	{
		$session = new Zend_Session_Namespace('login');
		if(!isset($session->priority)) return $this->_helper->redirector('index', 'index', null, array());

		$registry = Zend_Registry::getInstance();

		$this->name = strtolower(substr(get_class($this), 0, -10));
		$this->search_fields = $registry['config']->{$this->name}->search_fields->toArray();

  		is_null($this->model_name) ? $this->db = new CX_Common_Model($this->name) : $this->db = new $this->model_name();
  		
		$this->form = new CX_Common_Form();
		isset($registry['config']->{$this->name}->view->fields_values) && $values = $registry['config']->{$this->name}->view->fields_values->toArray();
		$this->form->createElements($this->db->getFields(), $registry['config']->{$this->name}->view->fields_name->toArray(), $values, $registry['config']->{$this->name}->require_fields->toArray());
		
		//refine! no merge!
		$this->view->assign(array_merge($registry['config']->{$this->name}->view->toArray(), $registry['config']->general->view->toArray()));

		$this->view->fields = $this->db->getFields();
		$this->view->controller = $this->name;
		$this->view->setEncoding('gb2312');
		
		$this->fields_readonly = $registry['config']->{$this->name}->fields_readonly->toArray();
		
		$this->view->company_name = $registry['company_name'];
	}
	
	public function indexAction()
	{
		$request = $this->getRequest();
		
		//处理搜索数据
		$index_form = clone $this->form;
		if ($request->isPost()) 
		{
			foreach($request->getPost() as $key => $value)
			{
				$value !== '' && $data[$key] = $value;
			}
			if ($index_form->isValidPartial($data)) 
			{
				$search_data = $index_form->getValues();
				unset($search_data['id']);
				foreach($search_data as $key => $value)
					if(is_null($value))	unset($search_data[$key]);
			}
		}
		
		$search_data_all = $search_data;
		if(isset($this->default_search))
		{
			foreach($this->default_search as $key => $value)
				$search_data_all[$key] = $value;
		}
		
		//查询数据
		$obscure = $request->getParam('obscure');
		$page = $request->getParam('page');
		$rs = $this->db->fetch('', $search_data_all, '', $obscure, $page);
		$this->view->data = $rs;
		$this->view->search_data = $search_data;
		$this->view->search_type = $obscure;
		$this->view->paginator = $this->db->paginator;
		
		//生成各种表单
		$add_form = clone $this->form;
		$url = '/' . $this->name . '/add';
		if($page) $url .= '/page/' . $page;
		$add_form->setAction($url);
		foreach($this->fields_readonly as $field)
		{
			$add_form->removeElement($field);
		}
		$this->view->add_form = $add_form;
		
		$search_form = clone $this->form;
		foreach($search_form->getElements() as $element)
		{
			$eName = $element->getName();
			if(!in_array($eName, $this->search_fields) && !($element instanceof Zend_Form_Element_Submit || $element instanceof Zend_Form_Element_Button))
				$search_form->removeElement($eName);
		}
			
		$search_form->setAction('/' . $this->name)
									->setName('sform')
									->setOptions(array('id' => 'sform'))
		  							->addElement('hidden', 'obscure', array('decorators' => array('ViewHelper', 'Errors'), 'value' => 0))
									->addElement('button', 'obutton', array(
															            'ignore'   => true,
															            'label'    => '模糊搜索',
															            'decorators' => array('ViewHelper', 'Errors'),
															            'onclick' => 'sform.obscure.value=1; sform.submit();'
															        ));   	
			
		foreach($this->search_fields as $key)
		{
			if ($search_form->$key instanceof Zend_Form_Element_Select)
			{
				$options = $search_form->$key->getMultiOptions();
				$search_form->$key->clearMultiOptions()
								  ->addMultiOption('', '全部')
								  ->addMultiOptions($options);
			}
		}
		$this->view->search_form = $search_form;
		
		$edit_form = clone $this->form;
		$url = '/' . $this->name . '/edit';
		if($page) $url .= '/page/' . $page;
		$edit_form->setAction($url)
				  ->setName('edit_form');
		foreach($this->fields_readonly as $field)
		{
			$edit_form->removeElement($field);
		}
		$this->view->edit_form = $edit_form;
		
		$multiedit_form = clone $this->form;
		$url = '/' . $this->name . '/edit';
		if($page) $url .= '/page/' . $page;
		$multiedit_form->setAction($url)
					   ->setName('multi_edit_form');
		foreach($this->fields_readonly as $field)
		{
			$multiedit_form->removeElement($field);
		}
		$this->view->multiedit_form = $multiedit_form;
		
		$this->view->normal_form = $this->form;
		
		array_shift($this->view->fields);
		$this->renderScript('common.phtml');
	}
	
	public function addAction()
	{
		$request = $this->getRequest();
		$page = $request->getParam('page');
		$param = array();
		$page && $param['page'] = $page;
		
		if ($request->isPost()) 
		{
		  if ($this->form->isValid($request->getPost())) 
		  {
		    $data = $this->form->getValues();
		    $this->db->save($data);
		  }
		}
		
		return $this->_helper->redirector('index', null, null, $param);
	}
	
	public function editAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam('id');
		$fields = $request->getParam('check_field');
		$page = $request->getParam('page');
		$param = array();
		$page && $param['page'] = $page;
		
		if($id)
		{
		    if($request->isPost()) 
		    {
		      //if ($this->form->isValid($request->getPost())) 
		      //{
		        $data = $request->getPost();//$this->form->getValues();

		    	foreach($data as $key => $value)
		    		if (!in_array($key, $this->view->fields)) unset($data[$key]);

			    strstr($id, '|') && $id = explode('|', $id);
			    if(is_array($id))
			    {
			    	$fields == '' && $fields = array();
			    	foreach($data as $key => $value)
			    		if (!in_array($key, $fields)) unset($data[$key]);
			    }
		        $this->db->save($data, $id);
		    //}
		    }
		}
		return $this->_helper->redirector('index', null, null, $param);
	}
  
  	public function deleteAction()
	{
		$request = $this->getRequest();
		$page = $request->getParam('page');
		$param = array();
		$page && $param['page'] = $page;
		$id = $request->getParam('id');
		strstr($id, '|') && $id = explode('|', $id);
		if($id)
		{
		  $this->db->delete($id);
		}
		return $this->_helper->redirector('index', null, null, $param);
	}
}
