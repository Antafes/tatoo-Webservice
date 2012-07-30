<?php
$key = 's8sFXYaLPj4DyAylI67t';

if (!$_POST['key'] ||$_POST['key'] != $key)
	die('You should not be here!');

require_once(dirname(__FILE__).'/lib/xml.php');
require_once(dirname(__FILE__).'/lib/input.php');
require_once(dirname(__FILE__).'/lib/output.php');
require_once(dirname(__FILE__).'/lib/message.php');

$xml = new xml(dirname(__FILE__).'/schema/');
$result = $xml->parseXML($_POST['message']);

if ($result == 'ok')
{
	switch ($xml->getType()) {
		case 'gameUpload':
			$gameHandler = new gameInputHandler();
			if ($gameHandler->import($xml->getData()))
				echo message::createResponse($xml->getMessageID());
			else
				echo message::createResponse($xml->getMessageID(), 'alreadyExisting');
			break;
	}
}
else
{
	if ($result == 'parsingFailed')
		echo message::createResponse($xml->getMessageID(), $result, $_POST['message']);
	else
		echo message::createResponse($xml->getMessageID(), $result);
}