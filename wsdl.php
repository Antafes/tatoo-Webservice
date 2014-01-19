<?php
require_once(__DIR__.'/lib/config.default.php');

$version = WS\WsdlHandler::getWsdl($_GET['version']);
$wsdl = file_get_contents(dirname(__FILE__).'/schema/'.$version.'.wsdl');
echo str_replace('{$baseUrl}', $GLOBALS['config']['baseUrl'], $wsdl);