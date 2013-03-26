<?php
require_once(dirname(__FILE__).'/lib/classes/SOAPHandler.class.php');

$SOAPServer = new SoapServer($GLOBALS['config']['baseUrl'].'/wsdl.php', array('encoding' => 'UTF-8'));
$SOAPServer->setClass(SOAPHandler);

$SOAPServer->handle();