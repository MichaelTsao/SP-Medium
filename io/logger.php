<?php
require_once dirname(__FILE__) . '/../model/autoload.php';
?>
<form name='log'>
Phone: <input type="text" name="phone"/>&nbsp;&nbsp;
LinkID: <input type="text" name="linkid"/>&nbsp;&nbsp;
Date: <input type="text" name="date"/>&nbsp;&nbsp;
All Month: <input type="checkbox" name="month" value=1 /> 
<input type="hidden" name='isshell' value=0>
<input type="button" value="Shell" name=shell onclick="log.isshell.value=1;log.submit();"/>
<input type="submit" value="Go" name=go />
</form>
<?php
if ($_REQUEST['isshell'])
{
	$date = !empty($_REQUEST['date']) ? $_REQUEST['date'] : date('Ymd');
	$logs = array('mo', 'mo_client', 'sr', 'sr_client');
	if (isset($_REQUEST['phone']))
		$key = $_REQUEST['phone'];
	elseif (isset($_REQUEST['linkid']))
		$key = $_REQUEST['linkid'];
				
	foreach ($logs as $log)
	{
		echo "<b>$log</b><br/>";
		$logger = new Log($log);
		if ($_REQUEST['month'])
		{
			$m = date('Ym');
			$cmd = "grep $key " . dirname(__FILE__) . '/../log/' . $log . '_' . $m . '*';
			$c = shell_exec($cmd);
			echo str_replace("\n", "<br>===============================<br>", str_replace('|', "<br>", $c)) . "<br/>";
		}
		else 
		{
			$cmd = "grep $key " . dirname(__FILE__) . '/../log/' . $log . '_' . $date . '*';
			$c = shell_exec($cmd);
			echo str_replace("\n", "<br>===============================<br>", str_replace('|', "<br>", $c)) . "<br/>";
		}
	} 	
}

if (isset($_REQUEST['go']))
{
	$date = !empty($_REQUEST['date']) ? $_REQUEST['date'] : date('Ymd');
	$logs = array('mo', 'mo_client', 'sr', 'sr_client');
	foreach ($logs as $log)
	{
		echo "<b>$log</b><br/>";
		$logger = new Log($log);
		if ($_REQUEST['month'])
		{
			$m = date('Ym');
			if (isset($_REQUEST['phone']))
				$key = $_REQUEST['phone'];
			elseif (isset($_REQUEST['linkid']))
				$key = $_REQUEST['linkid'];
				
			$cmd = "grep -l $key " . dirname(__FILE__) . '/../log/' . $log . '_' . $m . '*';
			$c = shell_exec($cmd);
			$a = explode("\n", $c);
			$day = array();
			foreach ($a as $b)
			{
				if ($b)
				{
					$z = explode($log, $b);
					$day[] = substr($z[1], 1, 8);
				}
			}
			foreach ($day as $d)
			{
				$data = $logger->read($d);
				foreach ($data as $one)
				{
					if ($one['linkid'] == $_REQUEST['linkid'] || $one['mobile'] == $_REQUEST['phone'])
					{
						foreach ($one as $key => $value)
						{
							echo $key . " : " . $value . "<br/>";
						}
						echo "===============================<br/>";
					}
				}
				unset($data);
			}
		}
		else 
		{
			$data = $logger->read($date);
			foreach ($data as $one)
			{
				if ($one['linkid'] == $_REQUEST['linkid'] || $one['mobile'] == $_REQUEST['phone'])
				{
					foreach ($one as $key => $value)
					{
						echo $key . " : " . $value . "<br/>";
					}
					echo "===============================<br/>";
				}
			}
		}
	} 
}
?>
