<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/*
	protected function _initTest()
	{
		$file = APPLICATION_PATH . "/configs/site.ini" ;
		//$config = new Zend_Config_Ini($file, null, array('allowModifications' => true));
		//$config->view->site_title = 'ָ�����ƽ̨';
		//$config->view->nav->default = 'a';//serialize(array('ָ��'=>'command', 'ͨ����'=>'sp', '�ͻ�'=>'user', '�˳�'=>array('index', 'logout')));
		$config = new Zend_Config(array(), true);
		$config->view = array();
		$config->view->title = 'ָ�����ƽ̨';
		$config->view->nav = array();
		$config->view->nav->default = array('ָ��'=>'command', 'ͨ����'=>'sp', '�ͻ�'=>'user', '�˳�'=>array('index', 'logout'), 'i|l'=>'ok');
		$config->view->nav->user = array('�ѷ���ָ��'=>'show','ȫ��ָ��'=>'list','�˳�'=>array('index', 'logout'));

		$writer = new Zend_Config_Writer_Array();
		$writer->write($file, $config);
	}
	*/
	
	protected function _initArgu()
	{
		$registry = Zend_Registry::getInstance();
		$session = new Zend_Session_Namespace('login');
		
		if ($_SERVER['SERVER_ADDR'] == '211.103.244.167')
			$registry['company_name'] = '��ͨ����';
		elseif ($_SERVER['SERVER_ADDR'] == '122.112.32.166')
			$registry['company_name'] = '';//�����Ǵ�
		else 
			$registry['company_name'] = '������̩';

		//Custom Global
		$registry['Prov'] = array(
		0=>'ȫ��',
		1=>'����',
		2=>'�Ϻ�',
		3=>'����',
		4=>'���ɹ�',
		5=>'����',
		6=>'�Ĵ�',
		7=>'���',
		8=>'����',
		9=>'����',
		10=>'ɽ��',
		11=>'ɽ��',
		12=>'�㶫',
		13=>'����',
		14=>'�½�',
		15=>'����',
		16=>'����',
		17=>'�ӱ�',
		18=>'����',
		19=>'�㽭',
		20=>'����',
		21=>'����',
		22=>'����',
		23=>'����',
		24=>'����',
		25=>'����',
		26=>'����',
		27=>'����',
		28=>'����',
		29=>'����',
		30=>'�ຣ',
		31=>'������'
		);
		
		$registry['GW_Prov'] = array(
		'cn' => 'ȫ��',
		'bj' => '����',
		'tj' => '���',
		'hb' => '�ӱ�',
		'sx' => 'ɽ��',
		'sd' => 'ɽ��',
		'gd' => '�㶫',
		'gx' => '����',
		'fj' => '����',
		'an' => '����',
		'sh' => '�Ϻ�',
		'js' => '����',
		'ah' => '����',
		'zj' => '�㽭',
		'hn' => '����',
		'ub' => '����',
		'un' => '����',
		'jx' => '����',
		'hl' => '������',
		'jl' => '����',
		'ln' => '����',
		'sc' => '�Ĵ�',
		'cq' => '����',
		'yn' => '����',
		'gz' => '����',
		'xz' => '����',
		'xj' => '�½�',
		'qh' => '�ຣ',
		'gs' => '����',
		'nx' => '����',
		'ax' => '����',
		'nm' => '���ɹ�'
		);
		
		
		//Normal Config
		$config = new Zend_Config(array(), true);
		
		$config->general = array();
		$config->sp = array();
		$config->user = array();
		$config->command = array();
		$config->assigned = array();
		$config->customer = array();
		$config->all = array();
		$config->content = array();
		$config->adjust = array();
		$config->quota = array();
		$config->setExtend('sp', 'general');
		$config->setExtend('user', 'general');
		$config->setExtend('command', 'general');
		$config->setExtend('assigned', 'general');
		$config->setExtend('customer', 'general');
		$config->setExtend('all', 'general');
		$config->setExtend('content', 'general');
		$config->setExtend('adjust', 'general');
		$config->setExtend('quota', 'general');

		$config->general->view = array();
		$config->general->view->site_title = 'ָ�����ƽ̨';
		//$config->general->view->nav = array();
		if($session->priority == 1)
			$config->general->view->nav = array('command'=>'ȫ��ָ��', 'assigned'=>'�ѷ���ָ��', 'sp'=>'ͨ����', 'user'=>'�ͻ�', 'adjust'=>'�Ż�', 'quota'=>'����', 'content'=>'����', 'index|logout'=>'�˳�');
		else
			$config->general->view->nav = array('customer'=>'�ͻ�ָ��', 'all'=>'ȫ��ָ��', 'index|logout'=>'�˳�');
		$config->general->per_page = 15;
		
		$config->sp->view = array();
		$config->sp->view->title = 'ͨ���̹���';
		$config->sp->view->fields_name = array('name'=>'ͨ������');
		$config->sp->view->fields_values = array();
		$config->sp->view->table_width = 500;
		$config->sp->view->menu_width = array('search' => 250, 'add' => 250, 'edit' => 250);
		$config->sp->view->menu_column = 2;
		$config->sp->fields_readonly = array();
		$config->sp->search_fields = array('name');
		$config->sp->require_fields = array('name');

		$config->user->view = array();
		$config->user->view->title = '�ͻ�����';
		$config->user->view->fields_name = array('username'=>'�û���', 'password'=>'����', 'name'=>'����', 'priority'=>'Ȩ��', 'send_mt'=>'�Ƿ�MT', 'send_sub_mr'=>'Sub��MR', 'ip'=>'��¼IP');
		$config->user->view->fields_values = array('priority' => array('1'=>'����Ա', '2'=>'�ͻ�'), 'send_mt' => array('0' => '����', '1' => '��'), 'send_sub_mr' => array('0' => '����', '1' => '��'));
		$config->user->view->table_width = 1000;
		$config->user->view->menu_width = array('search' => 400, 'add' => 450, 'edit' => 480);
		$config->user->view->menu_column = 2;
		$config->user->fields_readonly = array();
		$config->user->search_fields = array('username', 'priority');
		$config->user->require_fields = array('username','password','priority');
		
		$config->content->view = array();
		$config->content->view->title = '����';
		$config->content->view->fields_name = array('command_id'=>'ָ��', 'content'=>'����');
		$config->content->view->fields_values = array();
		$config->content->view->table_width = 2500;
		$config->content->view->menu_width = array('search' => 650, 'add' => 800, 'edit' => 800);
		$config->content->view->menu_column = 4;
		$config->content->fields_readonly = array();
		$config->content->search_fields = array('command_id');
		$config->content->require_fields = array('command_id', 'content');
		
		$config->adjust->view = array();
		$config->adjust->view->title = '�Ż�';
		$config->adjust->view->fields_name = array('client'=>'�ͻ�', 'time'=>'����', 'platform'=>'ҵ����', 'percent'=>'�ٷֱ�', 'prov' => 'ʡ��');
		$config->adjust->view->fields_values = array('platform' => array(1 => "SMS", 2 => "MMS", 3 => "IVR", 4 => "aPay"), 'prov' => $registry['GW_Prov']);
		$config->adjust->view->table_width = 800;
		$config->adjust->view->menu_width = array('search' => 600, 'add' => 600, 'edit' => 600);
		$config->adjust->view->menu_column = 4;
		$config->adjust->fields_readonly = array();
		$config->adjust->search_fields = array('client', 'time');
		$config->adjust->require_fields = array('client', 'time', 'percent');
		
		$config->quota->view = array();
		$config->quota->view->title = '����';
		$config->quota->view->fields_name = array('client'=>'�ͻ�', 'prov'=>'ʡ��', 'platform'=>'ҵ����', 'quota'=>'����');
		$config->quota->view->fields_values = array('platform' => array(1 => "SMS", 2 => "MMS", 3 => "IVR", 4 => "aPay"), 'prov' => $registry['GW_Prov']);
		$config->quota->view->table_width = 800;
		$config->quota->view->menu_width = array('search' => 600, 'add' => 600, 'edit' => 600);
		$config->quota->view->menu_column = 4;
		$config->quota->fields_readonly = array();
		$config->quota->search_fields = array('client');
		$config->quota->require_fields = array('client', 'platform', 'prov', 'quota');
		
		$config->command->view = array();
		$config->command->view->title = 'ȫ��ָ�����';
		$config->command->view->fields_name = array('name'=>'ҵ������', 'spid'=>'ͨ����', 'cmd_type'=>'ָ�����', 'prov'=>'ʡ��', 'operator'=>'��Ӫ��', 'content'=>'ָ������', 'content_type'=>'ָ������', 'port'=>'�˿�', 'long_code'=>'������', 'platform'=>'ҵ����', 'fee'=>'�ʷ�', 'fee_type'=>'�ʷ�����', 'userid' => '�ͻ�', 'unique_name'=>'������', 'unique_command'=>'����ָ��', 'assign_time'=>'����ʱ��', 'assignable'=>'����״̬');
		$config->command->view->fields_values = array('content_type' => array(1 => "ģ��", 2 => "��ȷ"), 'cmd_type' => array(1 => "ȫ��", 2 => "����"), 'operator' => array(1 => "�ƶ�", 2 => "��ͨ", 3 => "����"), 'platform' => array(1 => "SMS", 2 => "MMS", 3 => "IVR", 4 => "aPay"), 'fee_type' => array(1 => "�㲥", 2 => "����"), 'prov' => $registry['Prov'], 'assignable' => array(1 => '�ɷ���', 0 => '���ɷ���'));
		$config->command->view->table_width = 2500;
		$config->command->view->menu_width = array('search' => 650, 'add' => 800, 'edit' => 800);
		$config->command->view->menu_column = 4;
		$config->command->fields_readonly = array('assignable');
		$config->command->search_fields = array('spid', 'prov', 'operator', 'platform', 'content', 'assignable');
		$config->command->require_fields = array('spid', 'cmd_type', 'prov', 'operator', 'content', 'content_type', 'platform', 'fee', 'fee_type', 'userid', 'unique_name', 'unique_command', 'assign_time');
		
		$config->assigned->view = array();
		$config->assigned->view->title = '�ѷ���ָ�����';
		$config->assigned->view->fields_name = array('name'=>'ҵ������', 'spid'=>'ͨ����', 'cmd_type'=>'ָ�����', 'prov'=>'ʡ��', 'operator'=>'��Ӫ��', 'content'=>'ָ������', 'content_type'=>'ָ������', 'port'=>'�˿�', 'long_code'=>'������', 'platform'=>'ҵ����', 'fee'=>'�ʷ�', 'fee_type'=>'�ʷ�����', 'userid' => '�ͻ�', 'unique_name'=>'������', 'unique_command'=>'����ָ��', 'assign_time'=>'����ʱ��', 'assignable'=>'����״̬');
		$config->assigned->view->fields_values = array('content_type' => array(1 => "ģ��", 2 => "��ȷ"), 'cmd_type' => array(1 => "ȫ��", 2 => "����"), 'operator' => array(1 => "�ƶ�", 2 => "��ͨ", 3 => "����"), 'platform' => array(1 => "SMS", 2 => "MMS", 3 => "IVR", 4 => "aPay"), 'fee_type' => array(1 => "�㲥", 2 => "����"), 'prov' => $registry['Prov'], 'assignable' => array(1 => '�ɷ���', 0 => '���ɷ���'));
		$config->assigned->view->table_width = 2500;
		$config->assigned->view->menu_width = array('search' => 650, 'add' => 800, 'edit' => 800);
		$config->assigned->view->menu_column = 4;
		$config->assigned->fields_readonly = array('assign_time');
		$config->assigned->search_fields = array('userid', 'unique_name', 'unique_command');
		$config->assigned->require_fields = array('spid', 'cmd_type', 'prov', 'operator', 'content', 'content_type', 'port', 'long_code', 'platform', 'fee', 'fee_type', 'userid', 'unique_name', 'unique_command', 'assign_time');
		
		$config->customer->view = array();
		$config->customer->view->title = '�ͻ�ָ��';
		$config->customer->view->fields_name = array('name'=>'ҵ������', 'spid'=>'ͨ����', 'cmd_type'=>'ָ�����', 'prov'=>'ʡ��', 'operator'=>'��Ӫ��', 'content'=>'ָ������', 'content_type'=>'ָ������', 'port'=>'�˿�', 'long_code'=>'������', 'platform'=>'ҵ����', 'fee'=>'�ʷ�', 'fee_type'=>'�ʷ�����', 'userid' => '�ͻ�', 'unique_name'=>'������', 'unique_command'=>'����ָ��', 'assign_time'=>'����ʱ��', 'assignable'=>'����״̬');
		$config->customer->view->fields_values = array('content_type' => array(1 => "ģ��", 2 => "��ȷ"), 'cmd_type' => array(1 => "ȫ��", 2 => "����"), 'operator' => array(1 => "�ƶ�", 2 => "��ͨ", 3 => "����"), 'platform' => array(1 => "SMS", 2 => "MMS", 3 => "IVR", 4 => "aPay"), 'fee_type' => array(1 => "�㲥", 2 => "����"), 'prov' => $registry['Prov'], 'assignable' => array(1 => '�ɷ���', 0 => '���ɷ���'));
		$config->customer->view->table_width = 2500;
		$config->customer->view->menu_width = array('search' => 650, 'add' => 800, 'edit' => 800);
		$config->customer->view->menu_column = 4;
		$config->customer->fields_readonly = array('assign_time');
		$config->customer->search_fields = array('unique_name', 'unique_command');
		$config->customer->require_fields = array('spid', 'cmd_type', 'prov', 'operator', 'content', 'content_type', 'port', 'long_code', 'platform', 'fee', 'fee_type', 'userid', 'unique_name', 'unique_command', 'assign_time');

		$config->all->view = array();
		$config->all->view->title = 'ȫ��ָ��';
		$config->all->view->fields_name = array('name'=>'ҵ������', 'spid'=>'ͨ����', 'cmd_type'=>'ָ�����', 'prov'=>'ʡ��', 'operator'=>'��Ӫ��', 'content'=>'ָ������', 'content_type'=>'ָ������', 'port'=>'�˿�', 'long_code'=>'������', 'platform'=>'ҵ����', 'fee'=>'�ʷ�', 'fee_type'=>'�ʷ�����', 'userid' => '�ͻ�', 'unique_name'=>'������', 'unique_command'=>'����ָ��', 'assign_time'=>'����ʱ��', 'assignable'=>'����״̬');
		$config->all->view->fields_values = array('content_type' => array(1 => "ģ��", 2 => "��ȷ"), 'cmd_type' => array(1 => "ȫ��", 2 => "����"), 'operator' => array(1 => "�ƶ�", 2 => "��ͨ", 3 => "����"), 'platform' => array(1 => "SMS", 2 => "MMS", 3 => "IVR", 4 => "aPay"), 'fee_type' => array(1 => "�㲥", 2 => "����"), 'prov' => $registry['Prov'], 'assignable' => array(1 => '�ɷ���', 0 => '���ɷ���'));
		$config->all->view->table_width = 2500;
		$config->all->view->menu_width = array('search' => 650, 'add' => 800, 'edit' => 800);
		$config->all->view->menu_column = 4;
		$config->all->fields_readonly = array('assignable');
		$config->all->search_fields = array('spid', 'prov', 'operator', 'platform', 'content', 'assignable');
		$config->all->require_fields = array('spid', 'cmd_type', 'prov', 'operator', 'content', 'content_type', 'port', 'long_code', 'platform', 'fee', 'fee_type', 'userid', 'unique_name', 'unique_command', 'assign_time');
		
		$registry['config'] = $config;

	}
	
	protected function _initAutoload()
	{
		$resourceLoader = new Zend_Loader_Autoloader_Resource(array(
		    'basePath'  => APPLICATION_PATH,
		    'namespace' => 'CX',
		));
		$resourceLoader->addResourceType('con', 'common/', 'Common');
		
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->pushAutoloader($resourceLoader);
	}

}

