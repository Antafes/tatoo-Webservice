<?php
require_once(dirname(__FILE__).'/config.default.php');
require_once(dirname(__FILE__).'/lib/classes/wsdlHandler.php');

$version = WsdlHandler::getWsdl($_GET['version']);
$wsdl = file_get_contents(dirname(__FILE__).'/schema/'.$version.'.wsdl');
echo str_replace('{$baseUrl}', $GLOBALS['config']['baseUrl'], $wsdl);