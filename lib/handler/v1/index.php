<?php
require_once(__DIR__.'/../../config.default.php');
require_once(__DIR__.'/SOAPHandler.php');

$SOAPServer = new SoapServer(
	$GLOBALS['config']['baseUrl'].'/wsdl.php?version=1',
	array(
		'encoding' => 'UTF-8',
		'cache_wsdl' => WSDL_CACHE_NONE
	)
);
$SOAPServer->setClass(SOAPHandler);

$SOAPServer->handle();