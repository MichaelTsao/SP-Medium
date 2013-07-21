<?php
require_once dirname(__FILE__) . '/../model/autoload.php';

$path = dirname(__FILE__) . '/../log/';
$files = array(
	'mo_kernel',
	'sr_client',
	'mo_client'
);

$ok = 1;
foreach ($files as $f)
{
	$file = $path . $f . '_' . date('Ymd') . '.log';
	if (file_exists($file)) 
	{
	    if ( time() - filemtime($file) > 600 )
	    {
	    	$ok = 0;
	    	break;
	    }
	}
	else 
		$ok = 0;
}

if ($ok == 0)
{
	$output = shell_exec('cd ' . dirname(__FILE__) . '/../sys/; ./restart.sh;');
}

