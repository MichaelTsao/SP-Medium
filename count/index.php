<?php
@session_start(); 
require_once dirname(__FILE__) . '/../model/autoload.php';

if ($_REQUEST['who'] == 'money')
{
	$_SESSION['count_login'] = 1;
}

if (!isset($_SESSION['count_login']))
{
	echo "<div align=center><img src='you.jpg'><br><br>Who Are You! <br><br><form method=post><input type=password name=who></form></div>";
	exit();
}

$keys = array(
		'op',
		'client',
		'channel',
		'platform',
		'time'
		);

$recv = new Recv($keys);
$argu = $recv->getData();
$db = new DB();

$stat = $_REQUEST['stat'];
$stat_name = array('time' => '时间', 
					'prov' => '省份',
					'client' => '客户',
					'platform' => '业务线'
				);
if ($stat)
{
	list($mo, $mt, $money, $ivr_sec, $ivr_fee) = $db->getStat($stat, $argu);
	$title = array($stat_name[$stat], '上行', '成功下行', '失败下行', '总下行量', '收入（元）', '时长（秒）', '收入（元）', '总收入（元）');
  	
	$mo_keys = array_keys($mo) ? array_keys($mo) : array();
  	$ivr_keys = array_keys($ivr_sec) ? array_keys($ivr_sec) : array();
  	$keys_arr = array_unique(array_merge($mo_keys, $ivr_keys));
	foreach ($keys_arr as $key)
	{
		$all['mo'] += $mo[$key];
		$all['succ_mt'] += $mt[$key][1];
		$all['fail_mt'] += $mt[$key][0];
		$all['mt'] += $mt[$key][1] + $mt[$key][0];
		$all['money'] += $money[$key] / 100;
		$all['ivr_sec'] += $ivr_sec[$key];
		$all['ivr_fee'] += $ivr_fee[$key] / 100;
	}
}

$op = array(1=>'移动', 2=>'联通', 3=>'电信');
$platform = array(1=>'SMS', 2=>'MMS', 3=>'IVR');
$channel = $db->getSP();
$client = $db->getClient();
$provs = Config::getConfig('prov_name');
$company_name = Config::getConfig('company_name');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<link href="css/main.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="js/main.js"></script>
<title>统计系统</title>
</head>

<body>
<div align="center"><?php echo $company_name;?>统计系统</div>
<br/>
<div align="center" class="normal">
	<form method="post">
	维度：<select name=stat>
	<option value="time">时间</option>
	<option value="prov">地区</option>
	<option value="client">客户</option>
	<option value="platform">业务线</option>
	</select>
	&nbsp;&nbsp;
	日期：<select name=time>
	<?php $year = date('Y') - 1;
		for ($m = 1; $m <= 12; $m++)
		{
			$m < 10 && $m = '0'.$m;
			echo "<option value=$year-$m>$year-$m</option>\n";
		}
		$year++;
		for ($m = 1; $m < date('n'); $m++)
		{
			$m < 10 && $m = '0'.$m;
			echo "<option value=$year-$m>$year-$m</option>\n";
		}
	?>
	<option value=0 selected>全月</option>
	<?php for ($i = 1; $i <= 31; $i++): ?>
	<?php $day = date('Y-m-') . ($i<10 ? '0' : '') . $i;?>
	<option value=<?php echo $day;?>><?php echo $day;?></option>
	<?php endfor;?>
	</select>
	&nbsp;&nbsp;
	运营商：<select name=op>
	<option value=0>全部</option>
	<?php foreach ($op as $id => $name):?>
	<option value=<?php echo $id;?>><?php echo $name;?></option>
	<?php endforeach;?>
	</select>
	&nbsp;&nbsp;
	业务线：<select name=platform>
	<option value=0>全部</option>
	<?php foreach ($platform as $id => $name):?>
	<option value=<?php echo $id;?>><?php echo $name;?></option>
	<?php endforeach;?>
	</select>
	&nbsp;&nbsp;
	通道商：<select name=channel>
	<option value=0>全部</option>
	<?php foreach ($channel as $id => $name):?>
	<option value=<?php echo $id;?>><?php echo $name;?></option>
	<?php endforeach;?>
	</select>
	&nbsp;&nbsp;
	渠道商：<select name=client>
	<option value=0>全部</option>
	<?php foreach ($client as $id => $name):?>
	<option value=<?php echo $id;?>><?php echo $name;?></option>
	<?php endforeach;?>
	</select>
	&nbsp;&nbsp;
	<input type="submit" value="查" />
	</form>
</div>
<div align="center">&nbsp;</div>
<?php if ($mo || $mt || $ivr_sec):?>
<div align="center">
<table  align=center width=95% border=1 cellspacing="0" cellpadding="2" bordercolorlight="#000000" bordercolordark="#ffffff">
  <tr>
  	<td colspan="100" align="center" bgcolor="#5EF356">
  		<?php 
  			foreach ($argu as $k => $v)
  			{
  				if ($v == 0)
  				{
  					unset($argu[$k]);
  				}
  				else 
  				{
  					if ($k != 'time')
  					{
  						$d = $$k;
  						$argu_show[$k] = $d[$v];
  					}
  					elseif ($v == -1)
  					{
  						$argu_show[$k] = '上月';
  					}
  					else 
  					{
  						$argu_show[$k] = $v;
  					}
  				}
  			}
  			if (empty($argu_show))
  				echo "&nbsp;";
  			else 
  				echo implode(' - ', $argu_show);
  		?>
  	</td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  <td colspan="5" align="center">SMS & MMS</td>
  <td colspan="2" align="center">IVR</td>
  <td>&nbsp;</td>
  </tr>
  <tr>
  <?php foreach ($title as $t):?>
    <td scope="col"><b><?php echo $t;?></b></td>
  <?php endforeach;?>
  </tr>
  <?php 
  		$mo_keys = array_keys($mo) ? array_keys($mo) : array();
  		$ivr_keys = array_keys($ivr_sec) ? array_keys($ivr_sec) : array();
  		$keys_arr = array_unique(array_merge($mo_keys, $ivr_keys));
  ?>
  <?php foreach ($keys_arr as $key):?>
  <tr onMouseover="this.style.backgroundColor = '#AADDFF'" onMouseout="this.style.backgroundColor = ''">
    <td><?php 
    		if ($stat == 'time')
    		{
    			$key_name = $key;
    			$argu['stat'] = "client";
    		}
    		if ($stat == 'prov')
    		{
    			$key_name = $provs[$key];
    			$argu['stat'] = "client";
    		}
    		if ($stat == 'client')
    		{
    			$key_name = $client[$key];
    			$argu['stat'] = "prov";
    		}
    		if ($stat == 'platform')
    		{
    			$key_name = $platform[$key];
    			$argu['stat'] = "prov";
    		}
    		
    		$argu[$stat] = $key;
    		$aa = array();
    		foreach ($argu as $k => $v)
    		{
    			$aa[] = "$k=$v";
    		}
    		$key_argu = implode('&', $aa);
    		echo "<a href=\"?$key_argu\">$key_name</a>"
    	?>
    </td>
    <td><?php echo $mo[$key];?></td>
    <td><?php echo $mt[$key][1] == '' ? 0 : $mt[$key][1];?></td>
    <td><?php echo $mt[$key][0] == '' ? 0 : $mt[$key][0];?></td>
    <td><?php echo $mt[$key][1] + $mt[$key][0] == '' ? 0 : $mt[$key][1] + $mt[$key][0];?></td>
    <td><?php echo $money[$key] == '' ? 0 : $money[$key] / 100;?></td>
    <td><?php echo $ivr_sec[$key] == '' ? 0 : $ivr_sec[$key];?></td>
    <td><?php echo $ivr_fee[$key] == '' ? 0 : $ivr_fee[$key] / 100;?></td>
    <td><?php echo ($ivr_fee[$key] == '' && $money[$key] == '') ? 0 : ($ivr_fee[$key] + $money[$key]) / 100;?></td>
  </tr>
  <?php endforeach;?>
  
  <tr bgcolor="#FBB5F8">
    <td>汇总</td>
    <td><?php echo $all['mo'];?></td>
    <td><?php echo $all['succ_mt'];?></td>
    <td><?php echo $all['fail_mt'];?></td>
    <td><?php echo $all['mt'];?></td>
    <td><?php echo $all['money'];?></td>
    <td><?php echo $all['ivr_sec'];?></td>
    <td><?php echo $all['ivr_fee'];?></td>
    <td><?php echo ($all['money'] + $all['ivr_fee']);?></td>
  </tr>
</table>
</div>
<?php endif;?>
</body>
</html>
