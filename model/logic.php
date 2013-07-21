<?php
class Logic
{
	static public function getMsgID()
	{
		return date("YmdHis") . rand(1000, 9999);
	}
	
	static public function getSKFeeType($platform, $type)
	{
		if ($platform == 1)
		{
			if ($type == 1)
				$fee_type = 11;
			else 
				$fee_type = 10;
		}
		elseif ($platform == 2)
		{
			if ($type == 1)
				$fee_type = 21;
			else 
				$fee_type = 20;
		}
		else 
			$fee_type = 0;
			
		return $fee_type;
	}
	
	static public function getResult($r)
	{
		$res = preg_match_all( "/\<message-result\>(.*?)\<\/message-result\>/", $r, $result );
		if ($res)
			return $result[1][0];
		else
			return false;
	}
	
	static public function getOperator($phone, $put_id=0)
	{
		$ops = Config::getConfig('operators');
		$op_name = Config::getConfig('op_name');
		
		if (strlen($phone) < 3)
			return '';
		
		$phone_key = substr($phone, 0, 3);
		if (array_key_exists($phone_key, $ops))
		{
			$op_id = $ops[$phone_key];
			if ($put_id)
				return $op_id;
			else 
				return $op_name[$op_id];
		}
		else
			return '';
	}
	
	/**
	 * @desc  im:十进制数转换成三十六机制数
	 * @param (int)$num 十进制数
	 * return 返回：三十六进制数
	 */
	static function get_char($num) {
	  $num = intval($num);
	  if ($num <= 0)
	    return false;
	  $charArr = array("0","1","2","3","4","5","6","7","8","9",'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
	  $char = '';
	  do {
	    $key = ($num - 1) % 36;
	    $char= $charArr[$key] . $char;
	    $num = floor(($num - $key) / 36);
	  } while ($num > 0);
	  return $char;
	}
	
	/**
	 * @desc  im:三十六进制数转换成十机制数
	 * @param (string)$char 三十六进制数
	 * return 返回：十进制数
	 */
	static function get_num($char){
	  $array=array("0","1","2","3","4","5","6","7","8","9","A", "B", "C", "D","E", "F", "G", "H", "I", "J", "K", "L","M", "N", "O","P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y","Z");
	  $len=strlen($char);
	  for($i=0;$i<$len;$i++){
	    $index=array_search($char[$i],$array);
	    $sum+=($index+1)*pow(36,$len-$i-1);
	  }
	  return $sum;
	}
}
