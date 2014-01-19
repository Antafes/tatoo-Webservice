<?php
/**
 * Redirect to the given location
 *
 * @author Neithan
 * @param string $location
 */
function redirect($location)
{
	header('Location: '.$location);
	die();
}

function classLoad($name)
{
	$dir = __DIR__.'/../classes/';
	$pieces = explode('\\', $name);
	$class = array_pop($pieces);

	$dir .= implode('/', $pieces) . '/';

	if(file_exists($dir . $class .'.php'))
	{
		require_once($dir . $class . '.php');
		return true;
	}
	elseif ($name == 'Smarty')
	{
		require_once(__DIR__.'/../smarty3/Smarty.class.php');
		return true;
	}

	return false;
}