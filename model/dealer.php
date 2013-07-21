<?php
class Dealer
{
	public function kongDefault()
	{
		
	}
	
	static public function kongLogRead($file)
	{
		$content = file_get_contents($file);
		$file_data = explode("\n", $content);
		foreach ($file_data as $i => $line)
		{
			if (trim($line) == '')
				continue;
				
			$cell = explode('[', $line);
			foreach ($cell as $j => $data)
			{
				$a = explode(']', $data);
				$result[$i][$j] = $a[0];
			}
		}
		
		return $result;
	} 
}