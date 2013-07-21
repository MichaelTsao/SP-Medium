<?php
function __autoload($classname)
{
	$path = dirname(__FILE__) . '/';
	require_once $path . strtolower($classname) . '.php';
}