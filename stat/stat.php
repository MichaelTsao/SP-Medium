<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

session_start();

if (empty($_SESSION['client_id']))
{
	header('Location: index.php');
}

$company_name = Config::getConfig('company_name');

$db = new DB();
if (isset($_REQUEST['stat']))
{
	$prov_name = Config::getConfig('prov_name');
	
	$argu['client'] = $_SESSION['client_id'];
	$argu['op'] = $_REQUEST['op'];
	$argu['time'] = $_REQUEST['time'];
	$group = $_REQUEST['group'];

	list($count, $fee) = $db->getClientStat($group, $argu); 
	
//	$date = str_replace('-', '', $_REQUEST['time']);
//	$op = $_REQUEST['op'];
//	$group = $_REQUEST['group'];
//	$plat = $_REQUEST['plat'];
//	
//	$db_conf = Config::getConfig('db_conf');
//	$db = new MySQL($db_conf);
//	$count = array();
//	$fee = array();
//	$status = array();
//	
//	$sql = "select status, linkid from sr_$date";
//	$rs = $db->Query($sql);
//	while ($row = $db->FetchArray($rs))
//	{
//		$status[$row['linkid']] = $row['status'];
//	}
//	
//	$links = array();
//	$sql = "select left(time, 10) as date, prov, platform, op, fee, linkid from mo_$date where send=1 and client=" . $_SESSION['client_id'];
//	$rs = $db->Query($sql);
//	while ($row = $db->FetchArray($rs))
//	{
//		if (isset($links[$row['linkid']]))
//			continue;
//			
//		if ($op > 0 && $row['op'] != $op)
//			continue;
//		
//		if (strtoupper($status[$row['linkid']]) != 'DELIVRD' && strtolower($status[$row['linkid']]) != 'deliver')
//			continue;
//			
//		if ($group == 'time')
//		{
//			$gkey = $row['date'];
//			if ($row['date'] == date('Y-m-d'))
//				continue;
//		}
//		else 
//			$gkey = $row['prov'];
//				
//		$count[$gkey][$row['platform']]++;
//		$fee[$gkey][$row['platform']] += $row['fee'];
//		
//		$links[$row['linkid']] = 1;
//	}
//	ksort($count);
}

function money($m)
{
	return $m / 100;
}

function number($n)
{
	empty($n) && $n = 0;
	return $n;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />

<title>数据统计</title>

<link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />

<link href="css/jquery.ennui.contentslider.css" rel="stylesheet" type="text/css" media="screen,projection" />

<script language="javascript" type="text/javascript">

function clearText(field)

{

    if (field.defaultValue == field.value) field.value = '';

    else if (field.value == '') field.value = field.defaultValue;

}



function checkAll(chked)

{

	obj_data = document.getElementsByName("checked_id");



	for(i=0; i<obj_data.length; i++)

	{

		obj_data[i].checked = chked;

	}

}



function getChecked()

{

	var id_array = [];

	obj_data = document.getElementsByName("checked_id");



	for(i=0; i<obj_data.length; i++)

	{

		if(obj_data[i].checked)

			id_array.push(obj_data[i].value);

	}

	return id_array;

}



function multiEdit()

{

	var ids = getChecked();

	document.getElementById('me_form_id').value = ids.join('|');

	document.getElementById('me_form').submit();

}



function setValue(id)

{

	var fields = new Array("name","spid","cmd_type","prov","operator","content","content_type","port","long_code","platform","fee","fee_type","assignable","prov1","prov2","prov3","prov4","prov5","prov6","prov7","prov8","prov9","prov10","prov11","prov12","prov13","prov14","prov15","prov16","prov17","prov18","prov19","prov20","prov21","prov22","prov23","prov24","prov25","prov26","prov27","prov28","prov29","prov30","prov31");

	for(i=0; i<fields.length; i++)

	{

		key = fields[i] + "_" + id;

		value = document.getElementById(key).innerHTML;

		if(value == "&nbsp;")

			value = "";

		target = document.getElementById('edit_form_' + fields[i]);

		if(target != undefined)

		{

			if(target.type == "text" || target.type == "hidden")

				target.value = value;

			else

			{

				for(j=0; j<target.length; j++)

				{

					if(target.options[j].innerHTML == value)

						target.options[j].selected = true;

				}

			}

		}

	}

	document.getElementById('edit_form_id').value = id;

}

</script>



<link media="screen" rel="stylesheet" href="css/colorbox.css" />

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>

<script src="js/jquery.colorbox.js"></script>

</head>



<style type="text/css"> 

/* CSS Document */



body { 

    font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; 

    color: #4f6b72; 

    background: #E6EAE9; 

}



a { 

    color: #4f6b72/*#c75f3e*/;

	text-decoration:none;

}



#mytable { 

    width: 95%; 

    padding: 0; 

    margin: 0; 

}



caption { 

    padding: 0 0 5px 0; 

    width: 700px;      

    font: italic 11px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; 

    text-align: right; 

}



th { 

    font: bold 11px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; 

    color: #4f6b72; 

    border-right: 1px solid #C1DAD7; 

    border-bottom: 1px solid #C1DAD7; 

    border-top: 1px solid #C1DAD7; 

    letter-spacing: 2px; 

    text-transform: uppercase; 

    text-align: left; 

    padding: 6px 6px 6px 12px; 

    background: #CAE8EA; 

}



th.nobg { 

    border-top: 0; 

    border-left: 0; 

    border-right: 1px solid #C1DAD7; 

    background: none; 

}



td { 

    border-right: 1px solid #C1DAD7; 

    border-bottom: 1px solid #C1DAD7; 

    background: #fff; 

    font-size:11px; 

    padding: 6px 6px 6px 12px; 

    color: #4f6b72; 

}



td.no { 

    border: 0px; 

}



td.alt { 

    background: #F5FAFA; 

    color: #797268; 

}



td.altt { 

    background: #C8F084; 

    color: #797268; 

}



td.pagi { 

    color: #4f6b72; 

    border-right: 1px solid #C1DAD7; 

    border-bottom: 1px solid #C1DAD7; 

    border-top: 1px solid #C1DAD7; 

    text-transform: uppercase; 

    padding: 6px 6px 6px 12px; 

    background: #CAE8EA; 

}



th.spec { 

    border-left: 1px solid #C1DAD7; 

    border-top: 0; 

    background: #fff; 

    font: bolder 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif, "新宋体";

}



th.specalt { 

    border-left: 1px solid #C1DAD7; 

    border-top: 0; 

    background: #f5fafa; 

    font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; 

    color: #797268; 

} 

/*---------for IE 5.x bug*/ 

html>body td{ font-size:11px;} 

</style> 



<body>

	

<div id="templatemo_header_wrapper">

	<div id="templatemo_header">

		<div id="site_logo">统计平台</div>

	</div> <!-- end of header -->

</div> <!-- end of header wrapper -->

	

<div id="templatemo_menu_wrapper">   

  <div id="templatemo_menu">

	  <ul>

	  	<li><a href="#" class="current"><?php echo $_SESSION['client_name']?>数据统计</a></li><li><a href="index.php">退出</a></li>    </ul>    	

  </div> <!-- end of menu -->

</div> <!-- end of menu wrapper -->



<div id="tempatemo_content_wrapper">

            <div id="column_w610">

            

	<div align=center>

<!--     Data Table     -->

<table id="mytable" cellspacing="0">

	

	<!--     Search Info     -->

 	 	

<tr><td class="pagi" colspan=88>
<form>
时间：
<select name="time">
<?php $d = $db->getClientStatDate($_SESSION['client_id']);?>
<?php foreach ($d as $dd):?>
<option value='<?php echo $dd;?>'<?php if ($dd == date('Y-m')) echo " selected";?>><?php echo $dd;?></option>
<?php endforeach;?>
</select>
&nbsp;&nbsp;
分组类型：
<select name="group">
<option value="time">时间</option>
<option value="prov">地区</option>
</select>
&nbsp;&nbsp;
运营商：
<select name="op">
<option value=0>全部</option>
<option value=1>移动</option>
<option value=2>联通</option>
</select>
<!-- 
业务线：
<select name="plat">
<option value=0>全部</option>
<option value=1>SMS</option>
<option value=2>MMS</option>
</select>
 -->
&nbsp;&nbsp;
<input type="submit" value="查询" name="stat">
</form>
	 	</td></tr>

 	

	<!--     Caption     -->
<?php if (!empty($count)):?>
<caption> </caption> 

  <tr> 
        <th scope="col">&nbsp;</th>

  	    <th scope="col" colspan=2 align="center">SMS</th>

        <th scope="col" colspan=2 align="center">MMS</th>

        <th scope="col" colspan=2 align="center">IVR</th>

        <th scope="col">&nbsp;</th>

  </tr>
  <tr> 

  	    <th scope="col">时间</th>

        <th scope="col">条数</th>

        <th scope="col">收入（元）</th>

        <th scope="col">条数</th>

        <th scope="col">收入（元）</th>

        <th scope="col">时间（秒）</th>

        <th scope="col">收入（元）</th>

        <th scope="col">收入合计（元）</th>
   </tr>



	<!--     Data Sheet     -->


		

		<!--     Data Row     -->
		<?php foreach ($count as $key => $value):?>
		<tr>

		<td class="alt">
		<?php 
		if ($group == 'time')
			echo $key;
		elseif ($group == 'prov')
			echo ($key == '' ? '未知' : $prov_name[$key]);
		?>
		</td>
		<td class="alt"><?php echo number($value[1]);?></td>
		<td class="alt"><?php echo money($fee[$key][1]);?></td>
		<td class="alt"><?php echo number($value[2]);?></td>
		<td class="alt"><?php echo money($fee[$key][2]);?></td>
		<td class="alt"><?php echo number($value[3]);?></td>
		<td class="alt"><?php echo money($fee[$key][3]);?></td>
		<td class="alt"><?php echo money($fee[$key][1] + $fee[$key][2] + $fee[$key][3]);?></td>

		</tr> 
		<?php 
		$all[1] += $value[1];
		$all[2] += $fee[$key][1];
		$all[3] += $value[2];
		$all[4] += $fee[$key][2];
		$all[5] += $value[3];
		$all[6] += $fee[$key][3];
		$all[7] += $fee[$key][1] + $fee[$key][2] + $fee[$key][3];
		endforeach;?>

    		<!--     Data Row End      -->


<tr>
	<td class="alt">汇总</td>
	<td class="alt"><?php echo number($all[1]);?></td>
	<td class="alt"><?php echo money($all[2]);?></td>
	<td class="alt"><?php echo number($all[3]);?></td>
	<td class="alt"><?php echo money($all[4]);?></td>
	<td class="alt"><?php echo number($all[5]);?></td>
	<td class="alt"><?php echo money($all[6]);?></td>
	<td class="alt"><?php echo money($all[7]);?></td>
</tr>
<tr><td class="pagi" colspan=46>

&nbsp;

	 	</td></tr>
	 	<?php endif;?>

  <!--    Data Sheet End    -->

  

</table>

</div>



            </div> <!-- end of column w610 -->

            <div class="cleaner"></div>

        <div class="cleaner"></div>

</div> <!-- end of content wrapper -->

	

<div id="templatemo_footer_wrapper">

	<div id="templatemo_footer">

        <div class="section_w920">

        Copyright &copy; 2011 <?php echo $company_name;?>

        </div>

        <div class="cleaner"></div>

    </div> <!-- end of footer -->

</div> <!-- end of footer -->



</body>

</html>
