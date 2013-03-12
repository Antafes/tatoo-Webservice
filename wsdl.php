<?php
require_once(dirname(__FILE__).'/config.default.php');

$wsdl = file_get_contents(dirname(__FILE__).'/lib/tatoo.wsdl');
echo str_replace('{$baseUrl}', $GLOBALS['config']['baseUrl'], $wsdl);