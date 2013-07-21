<?
/*
############################################################
#标准调用范例
#  include("class/class.config.php");
#  include("class/class.mysql.php");
#
#  $config = new Config;
#  $mysql = new TDatabase($config);  //new出的Config实例作为TDatabase类的实例的参数
#  $sql = "select UserName from user where ID = 1";
#  $result = $mysql->Query($sql);
#  if ($mysql->AffectedRows != 0)
#  {
#     $row = $mysql->FetchArray($result);
#     echo $row[0];
#  }
#  $mysql->DatabaseClose();
############################################################
*/

class MySQL 
{ 
	var $m_host; 
	var $m_port; 
	var $m_user; 
	var $m_password; 
	var $m_name; 
	var $m_link; 

	function Err($sql = "") 
	{
		global $HTTP_HOST;
		global $PHP_SELF;

		echo "<font color=red>error sql : </font><br>&nbsp;&nbsp;".$sql;
		echo "<br>";
		echo "<font color=red>error number : </font><br>&nbsp;&nbsp;".$this->GetErrno();
		echo "<br>";
		echo "<font color=red>error information : </font><br>&nbsp;&nbsp;".$this->GetError();
	} 

	public function __construct($config) 
	{
		$this->m_host     = $config['mDbHost'];
		$this->m_port     = $config['mDbPort']; 
		$this->m_user     = $config['mDbUser']; 
		$this->m_password = $config['mDbPassword']; 
		$this->m_name     = $config['mDbDatabase'];
		register_shutdown_function(array(&$this, '_DatabaseClose'));             
		$this->m_link=0;
	} 

	function _initconnection()
	{
		if ($this->m_link==0)
		{
			$real_host = $this->m_host.":".$this->m_port;    
			$this->m_link = mysql_connect($real_host,$this->m_user,$this->m_password) or die($this->Err()); 
			if ("" != $this->m_name)
			{
				mysql_select_db($this->m_name, $this->m_link) or die($this->Err());
			}             
		}
	}

	function SelectDb($database)
	{
		$this->m_name = $database;
		if ("" != $this->m_name)
		{
			if ($this->m_link == 0)
			{
				$this->_initconnection();
			}
			mysql_select_db($this->m_name, $this->m_link) or die($this->Err());
		}
	}

	function Query($sql, $Oper="")
	{
		if ($this->m_link == 0)
		{
			$this->_initconnection();
		}
		$result=mysql_query($sql,$this->m_link) or die($this->Err($sql)); 
		return $result; 
	} 
	
	function Query_Egm($SQL)
	{ 
		if ($this->m_link == 0)
		{
			$this->_initconnection();
		}
		$result = mysql_query($SQL,$this->m_link);
		return $result; 
	}
	function GetErrno()
	{
		if ($this->m_link == 0)
		{
			$this->_initconnection();
		}
		return mysql_errno($this->m_link);
	}
	function GetError()
	{
		if ($this->m_link == 0)
		{
			$this->_initconnection();
		}
		return mysql_error($this->m_link);
	}
	
	function FetchArray($result) 
	{ 
		if ($this->m_link == 0)
		{
			$this->_initconnection();
		}
		@$row=mysql_fetch_array($result); 
		return $row; 
	} 
	function FetchArray_new($result) 
	{ 
		if ($this->m_link == 0)
		{
			$this->_initconnection();
		}
		$row=mysql_fetch_array($result,MYSQL_ASSOC); 
		return $row; 
	}  
	function FetchRow($result) 
	{ 
		if ($this->m_link == 0)
		{
			$this->_initconnection();
		}
		$row=mysql_fetch_row($result); 
		return $row; 
	} 

	function FetchObject($result) 
	{ 
		if ($this->m_link == 0)
		{
			$this->_initconnection();
		}
		$row=mysql_fetch_object($result); 
		return $row; 
	} 

	function FreeResult(&$result) 
	{ 
		if ($this->m_link == 0)
		{
			$this->_initconnection();
		}
		return mysql_free_result($result) or die($this->Err()); 
	} 

	function NumRows($result) 
	{ 
		if ($this->m_link == 0)
		{
			$this->_initconnection();
		}
		$result=mysql_num_rows($result) or die($this->Err()); 
		return $result; 
	} 
	
	function DataSeek($result,$row_id) 
	{ 
		if ($this->m_link == 0)
		{
			$this->_initconnection();
		}
		$result=mysql_data_seek($result,$row_id);		
		return $result; 
	}

	function AffectedRows() 
	{ 
		if ($this->m_link == 0)
		{
			$this->_initconnection();
		}
		$result=mysql_affected_rows($this->m_link); 
		return $result; 
	}

	function NumFileds() 
	{ 
		if ($this->m_link == 0)
		{
			$this->_initconnection();
		}
		$result=mysql_num_fields($this->m_link); 
		return $result; 
	}

	function FiledName() 
	{ 
		if ($this->m_link == 0)
		{
			$this->_initconnection();
		}
		$result=mysql_field_name($this->m_link); 
		return $result; 
	}
					
	function DatabaseClose()
	{
		if(is_resource($this->m_link))
		{
			mysql_close($this->m_link) or die($this->Err());
		}
	}
	
	function _DatabaseClose()
	{
		if(is_resource($this->m_link))
		{
			mysql_close($this->m_link) or die($this->Err());
		}
	}
	
	function getInsertID()
	{
		if ($this->m_link == 0)
		{
			$this->_initconnection();
		}
		return mysql_insert_id($this->m_link);
	}
} 
?>
