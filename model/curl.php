<?php
class Curl
{
	/*
	 * curl²Ù×÷Àà
	 */

	static public function post($url, $param='')
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		if( $param )
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		}
		$output = curl_exec($ch);
		curl_close($ch);
		
		return $output;
	}
	
	public function encodeURL($url)
	{
		$u = explode('/', $url);
		foreach ($u as $k => $one)
		{
			if ($k % 2 == 0 && $k > 2)
				$u[$k] = urlencode($one);
		}
		return implode('/', $u);
	}
	
	static public function get($url)
	{
		$cu = curl_init();
		curl_setopt($cu, CURLOPT_URL, $url);
		curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
		$ret = curl_exec($cu);
		curl_close($cu);
		
		return $ret;
	}
}
