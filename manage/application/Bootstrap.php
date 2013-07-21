<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/*
	protected function _initTest()
	{
		$file = APPLICATION_PATH . "/configs/site.ini" ;
		//$config = new Zend_Config_Ini($file, null, array('allowModifications' => true));
		//$config->view->site_title = '指令管理平台';
		//$config->view->nav->default = 'a';//serialize(array('指令'=>'command', '通道商'=>'sp', '客户'=>'user', '退出'=>array('index', 'logout')));
		$config = new Zend_Config(array(), true);
		$config->view = array();
		$config->view->title = '指令管理平台';
		$config->view->nav = array();
		$config->view->nav->default = array('指令'=>'command', '通道商'=>'sp', '客户'=>'user', '退出'=>array('index', 'logout'), 'i|l'=>'ok');
		$config->view->nav->user = array('已分配指令'=>'show','全部指令'=>'list','退出'=>array('index', 'logout'));

		$writer = new Zend_Config_Writer_Array();
		$writer->write($file, $config);
	}
	*/
	
	protected function _initArgu()
	{
		$registry = Zend_Registry::getInstance();
		$session = new Zend_Session_Namespace('login');
		
		if ($_SERVER['SERVER_ADDR'] == '211.103.244.167')
			$registry['company_name'] = '创通无限';
		elseif ($_SERVER['SERVER_ADDR'] == '122.112.32.166')
			$registry['company_name'] = '';//飞宇智达
		else 
			$registry['company_name'] = '国华海泰';

		//Custom Global
		$registry['Prov'] = array(
		0=>'全国',
		1=>'北京',
		2=>'上海',
		3=>'云南',
		4=>'内蒙古',
		5=>'吉林',
		6=>'四川',
		7=>'天津',
		8=>'宁夏',
		9=>'安徽',
		10=>'山东',
		11=>'山西',
		12=>'广东',
		13=>'广西',
		14=>'新疆',
		15=>'江苏',
		16=>'江西',
		17=>'河北',
		18=>'河南',
		19=>'浙江',
		20=>'海南',
		21=>'湖北',
		22=>'湖南',
		23=>'甘肃',
		24=>'福建',
		25=>'西藏',
		26=>'贵州',
		27=>'辽宁',
		28=>'重庆',
		29=>'陕西',
		30=>'青海',
		31=>'黑龙江'
		);
		
		$registry['GW_Prov'] = array(
		'cn' => '全国',
		'bj' => '北京',
		'tj' => '天津',
		'hb' => '河北',
		'sx' => '山西',
		'sd' => '山东',
		'gd' => '广东',
		'gx' => '广西',
		'fj' => '福建',
		'an' => '海南',
		'sh' => '上海',
		'js' => '江苏',
		'ah' => '安徽',
		'zj' => '浙江',
		'hn' => '河南',
		'ub' => '湖北',
		'un' => '湖南',
		'jx' => '江西',
		'hl' => '黑龙江',
		'jl' => '吉林',
		'ln' => '辽宁',
		'sc' => '四川',
		'cq' => '重庆',
		'yn' => '云南',
		'gz' => '贵州',
		'xz' => '西藏',
		'xj' => '新疆',
		'qh' => '青海',
		'gs' => '甘肃',
		'nx' => '宁夏',
		'ax' => '陕西',
		'nm' => '内蒙古'
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
		$config->general->view->site_title = '指令管理平台';
		//$config->general->view->nav = array();
		if($session->priority == 1)
			$config->general->view->nav = array('command'=>'全部指令', 'assigned'=>'已分配指令', 'sp'=>'通道商', 'user'=>'客户', 'adjust'=>'优化', 'quota'=>'限量', 'content'=>'内容', 'index|logout'=>'退出');
		else
			$config->general->view->nav = array('customer'=>'客户指令', 'all'=>'全部指令', 'index|logout'=>'退出');
		$config->general->per_page = 15;
		
		$config->sp->view = array();
		$config->sp->view->title = '通道商管理';
		$config->sp->view->fields_name = array('name'=>'通道商名');
		$config->sp->view->fields_values = array();
		$config->sp->view->table_width = 500;
		$config->sp->view->menu_width = array('search' => 250, 'add' => 250, 'edit' => 250);
		$config->sp->view->menu_column = 2;
		$config->sp->fields_readonly = array();
		$config->sp->search_fields = array('name');
		$config->sp->require_fields = array('name');

		$config->user->view = array();
		$config->user->view->title = '客户管理';
		$config->user->view->fields_name = array('username'=>'用户名', 'password'=>'密码', 'name'=>'名字', 'priority'=>'权限', 'send_mt'=>'是否MT', 'send_sub_mr'=>'Sub发MR', 'ip'=>'登录IP');
		$config->user->view->fields_values = array('priority' => array('1'=>'管理员', '2'=>'客户'), 'send_mt' => array('0' => '不发', '1' => '发'), 'send_sub_mr' => array('0' => '不发', '1' => '发'));
		$config->user->view->table_width = 1000;
		$config->user->view->menu_width = array('search' => 400, 'add' => 450, 'edit' => 480);
		$config->user->view->menu_column = 2;
		$config->user->fields_readonly = array();
		$config->user->search_fields = array('username', 'priority');
		$config->user->require_fields = array('username','password','priority');
		
		$config->content->view = array();
		$config->content->view->title = '内容';
		$config->content->view->fields_name = array('command_id'=>'指令', 'content'=>'内容');
		$config->content->view->fields_values = array();
		$config->content->view->table_width = 2500;
		$config->content->view->menu_width = array('search' => 650, 'add' => 800, 'edit' => 800);
		$config->content->view->menu_column = 4;
		$config->content->fields_readonly = array();
		$config->content->search_fields = array('command_id');
		$config->content->require_fields = array('command_id', 'content');
		
		$config->adjust->view = array();
		$config->adjust->view->title = '优化';
		$config->adjust->view->fields_name = array('client'=>'客户', 'time'=>'日期', 'platform'=>'业务线', 'percent'=>'百分比', 'prov' => '省份');
		$config->adjust->view->fields_values = array('platform' => array(1 => "SMS", 2 => "MMS", 3 => "IVR", 4 => "aPay"), 'prov' => $registry['GW_Prov']);
		$config->adjust->view->table_width = 800;
		$config->adjust->view->menu_width = array('search' => 600, 'add' => 600, 'edit' => 600);
		$config->adjust->view->menu_column = 4;
		$config->adjust->fields_readonly = array();
		$config->adjust->search_fields = array('client', 'time');
		$config->adjust->require_fields = array('client', 'time', 'percent');
		
		$config->quota->view = array();
		$config->quota->view->title = '限量';
		$config->quota->view->fields_name = array('client'=>'客户', 'prov'=>'省份', 'platform'=>'业务线', 'quota'=>'条数');
		$config->quota->view->fields_values = array('platform' => array(1 => "SMS", 2 => "MMS", 3 => "IVR", 4 => "aPay"), 'prov' => $registry['GW_Prov']);
		$config->quota->view->table_width = 800;
		$config->quota->view->menu_width = array('search' => 600, 'add' => 600, 'edit' => 600);
		$config->quota->view->menu_column = 4;
		$config->quota->fields_readonly = array();
		$config->quota->search_fields = array('client');
		$config->quota->require_fields = array('client', 'platform', 'prov', 'quota');
		
		$config->command->view = array();
		$config->command->view->title = '全部指令管理';
		$config->command->view->fields_name = array('name'=>'业务名称', 'spid'=>'通道商', 'cmd_type'=>'指令类别', 'prov'=>'省份', 'operator'=>'运营商', 'content'=>'指令内容', 'content_type'=>'指令类型', 'port'=>'端口', 'long_code'=>'长代码', 'platform'=>'业务线', 'fee'=>'资费', 'fee_type'=>'资费类型', 'userid' => '客户', 'unique_name'=>'渠道项', 'unique_command'=>'分配指令', 'assign_time'=>'分配时间', 'assignable'=>'分配状态');
		$config->command->view->fields_values = array('content_type' => array(1 => "模糊", 2 => "精确"), 'cmd_type' => array(1 => "全网", 2 => "地网"), 'operator' => array(1 => "移动", 2 => "联通", 3 => "电信"), 'platform' => array(1 => "SMS", 2 => "MMS", 3 => "IVR", 4 => "aPay"), 'fee_type' => array(1 => "点播", 2 => "包月"), 'prov' => $registry['Prov'], 'assignable' => array(1 => '可分配', 0 => '不可分配'));
		$config->command->view->table_width = 2500;
		$config->command->view->menu_width = array('search' => 650, 'add' => 800, 'edit' => 800);
		$config->command->view->menu_column = 4;
		$config->command->fields_readonly = array('assignable');
		$config->command->search_fields = array('spid', 'prov', 'operator', 'platform', 'content', 'assignable');
		$config->command->require_fields = array('spid', 'cmd_type', 'prov', 'operator', 'content', 'content_type', 'platform', 'fee', 'fee_type', 'userid', 'unique_name', 'unique_command', 'assign_time');
		
		$config->assigned->view = array();
		$config->assigned->view->title = '已分配指令管理';
		$config->assigned->view->fields_name = array('name'=>'业务名称', 'spid'=>'通道商', 'cmd_type'=>'指令类别', 'prov'=>'省份', 'operator'=>'运营商', 'content'=>'指令内容', 'content_type'=>'指令类型', 'port'=>'端口', 'long_code'=>'长代码', 'platform'=>'业务线', 'fee'=>'资费', 'fee_type'=>'资费类型', 'userid' => '客户', 'unique_name'=>'渠道项', 'unique_command'=>'分配指令', 'assign_time'=>'分配时间', 'assignable'=>'分配状态');
		$config->assigned->view->fields_values = array('content_type' => array(1 => "模糊", 2 => "精确"), 'cmd_type' => array(1 => "全网", 2 => "地网"), 'operator' => array(1 => "移动", 2 => "联通", 3 => "电信"), 'platform' => array(1 => "SMS", 2 => "MMS", 3 => "IVR", 4 => "aPay"), 'fee_type' => array(1 => "点播", 2 => "包月"), 'prov' => $registry['Prov'], 'assignable' => array(1 => '可分配', 0 => '不可分配'));
		$config->assigned->view->table_width = 2500;
		$config->assigned->view->menu_width = array('search' => 650, 'add' => 800, 'edit' => 800);
		$config->assigned->view->menu_column = 4;
		$config->assigned->fields_readonly = array('assign_time');
		$config->assigned->search_fields = array('userid', 'unique_name', 'unique_command');
		$config->assigned->require_fields = array('spid', 'cmd_type', 'prov', 'operator', 'content', 'content_type', 'port', 'long_code', 'platform', 'fee', 'fee_type', 'userid', 'unique_name', 'unique_command', 'assign_time');
		
		$config->customer->view = array();
		$config->customer->view->title = '客户指令';
		$config->customer->view->fields_name = array('name'=>'业务名称', 'spid'=>'通道商', 'cmd_type'=>'指令类别', 'prov'=>'省份', 'operator'=>'运营商', 'content'=>'指令内容', 'content_type'=>'指令类型', 'port'=>'端口', 'long_code'=>'长代码', 'platform'=>'业务线', 'fee'=>'资费', 'fee_type'=>'资费类型', 'userid' => '客户', 'unique_name'=>'渠道项', 'unique_command'=>'分配指令', 'assign_time'=>'分配时间', 'assignable'=>'分配状态');
		$config->customer->view->fields_values = array('content_type' => array(1 => "模糊", 2 => "精确"), 'cmd_type' => array(1 => "全网", 2 => "地网"), 'operator' => array(1 => "移动", 2 => "联通", 3 => "电信"), 'platform' => array(1 => "SMS", 2 => "MMS", 3 => "IVR", 4 => "aPay"), 'fee_type' => array(1 => "点播", 2 => "包月"), 'prov' => $registry['Prov'], 'assignable' => array(1 => '可分配', 0 => '不可分配'));
		$config->customer->view->table_width = 2500;
		$config->customer->view->menu_width = array('search' => 650, 'add' => 800, 'edit' => 800);
		$config->customer->view->menu_column = 4;
		$config->customer->fields_readonly = array('assign_time');
		$config->customer->search_fields = array('unique_name', 'unique_command');
		$config->customer->require_fields = array('spid', 'cmd_type', 'prov', 'operator', 'content', 'content_type', 'port', 'long_code', 'platform', 'fee', 'fee_type', 'userid', 'unique_name', 'unique_command', 'assign_time');

		$config->all->view = array();
		$config->all->view->title = '全部指令';
		$config->all->view->fields_name = array('name'=>'业务名称', 'spid'=>'通道商', 'cmd_type'=>'指令类别', 'prov'=>'省份', 'operator'=>'运营商', 'content'=>'指令内容', 'content_type'=>'指令类型', 'port'=>'端口', 'long_code'=>'长代码', 'platform'=>'业务线', 'fee'=>'资费', 'fee_type'=>'资费类型', 'userid' => '客户', 'unique_name'=>'渠道项', 'unique_command'=>'分配指令', 'assign_time'=>'分配时间', 'assignable'=>'分配状态');
		$config->all->view->fields_values = array('content_type' => array(1 => "模糊", 2 => "精确"), 'cmd_type' => array(1 => "全网", 2 => "地网"), 'operator' => array(1 => "移动", 2 => "联通", 3 => "电信"), 'platform' => array(1 => "SMS", 2 => "MMS", 3 => "IVR", 4 => "aPay"), 'fee_type' => array(1 => "点播", 2 => "包月"), 'prov' => $registry['Prov'], 'assignable' => array(1 => '可分配', 0 => '不可分配'));
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

