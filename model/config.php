<?php
class Config
{
    static public function getConfig($property)
    {
    	$configFile = dirname(__FILE__) . '/../config/conf.php';
    	
        static $configs = array();
        if(array_key_exists($property,$configs))
        {
            return $configs[$property];
        }
        else
		{
            include($configFile);
            $config = isset($$property) ? $$property : '';
            $configs[$property]  = $config;
            return $config;
        }
        return false;
    }
}
?>
