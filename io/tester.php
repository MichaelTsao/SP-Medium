<?php
header("Content-type: text/html; charset=gb2312");
require_once dirname(__FILE__) . '/../model/autoload.php';

$platform = Config::getConfig('platform');
$op_name = Config::getConfig('op_name');
$db_conf = Config::getConfig('db_conf');
$db = new MySQL($db_conf);
$rs = $db->Query('set names gbk');
$rs = $db->Query('select b.content, a.unique_command, b.port, b.long_code, u.name, b.platform, b.operator, u.id from command_assign a, command b, user u where b.id=a.commandid and a.userid=u.id');
while ($row = $db->FetchArray($rs))
{
	$com[] = $row['id'] . "|" . $row['name'] . "|" . $platform[$row['platform']] . "|" . $op_name[$row['operator']] . "|" . $row['content'] . $row['unique_command'] . '=>' . $row['port'] . $row['long_code'];
}

$data_type = $_REQUEST['data_type'];
if (isset($_REQUEST['go']))
{
	$mobile = $_REQUEST['mobile'];
	empty($mobile) && $mobile = '13812345678';
	list($id, $name, $plat, $op, $cmd) = explode('|', $_REQUEST['command']);
	list($content, $longcode) = explode('=>', $cmd);
	if ($data_type == 'mo')
	{
		if ($plat == 'SMS')
			$r = Curl::post($_SERVER['HTTP_HOST'] . '/mo.php', "mobile=$mobile&carrier=$op&longcode=$longcode&channel=XGHRD000S00&linkid=" . rand(1000000, 9999999) . "&innerid=20110804220506133" . rand(100000, 999999) . "&fee=100&province=18&spid=$op&provincename=abc&content=$content");
		else 
			$r = Curl::post($_SERVER['HTTP_HOST'] . '/ivrdown.php', "mobile=$mobile&carrier=$op&channel=XGHRD000S00&fee=100&province=18&callno=$content&calltime=2012-6-25 12:03:00&halttime=2012-6-25 12:03:30&feeseconds=60");
	}
	elseif ($data_type == 'sub')
	{
		$r = Curl::post($_SERVER['HTTP_HOST'] . '/sub.php', "mobile=$mobile&msgSpCode=$longcode&msgContent=$content&msgFee=500&msgLinkid=" . rand(10000000, 99999999));
	}
	elseif ($data_type == 'unsub')
	{
		$r = Curl::post($_SERVER['HTTP_HOST'] . '/unsub_test.php', "mobile=$mobile&time=2012-07-18 17:00:00&command=$content&id=$id");
	}
	elseif ($data_type == 'apay')
	{
	  $r = Curl::post($_SERVER['HTTP_HOST'] . '/apay.php', "linkId=" . rand(1000000, 9999999) . "&fee=500&sign=".substr($content, 55)."&status=1");
	}
}
?>
<form>
<input type="text" name="mobile" value="<?php if (isset($_REQUEST['mobile'])) echo $_REQUEST['mobile']; else echo "13812345678";?>">
<select name='data_type'>
<option value='mo' <?php if ($data_type == 'mo') echo "selected";?>>MO/IVR</option>
<!-- <option value='mt'>MT</option> -->
<option value='sr' <?php if ($data_type == 'sr') echo "selected";?>>SR</option>
<option value='sub' <?php if ($data_type == 'sub') echo "selected";?>>Sub</option>
<option value='unsub' <?php if ($data_type == 'unsub') echo "selected";?>>unSub</option>
<option value='apay' <?php if ($data_type == 'apay') echo "selected";?>>aPay</option>
</select>
<br/>
<select name="command">
<?php foreach ($com as $one):?>
<option value="<?php echo $one;?>" <?php if ($_REQUEST['command'] == $one) echo "selected";?>><?php echo $one;?></option>
<?php endforeach;?>
</select>
<br/>
<input type="submit" name="go" value="Go" />
</form>
<br/>
<br/>
<?php echo $r; ?>
