<?php
$key = 's8sFXYaLPj4DyAylI67t';

if (!$_POST['key'] ||$_POST['key'] != $key)
	die('You should not be here!');

require_once(dirname(__FILE__).'/lib/xml.php');
require_once(dirname(__FILE__).'/lib/input.php');
require_once(dirname(__FILE__).'/lib/output.php');
require_once(dirname(__FILE__).'/lib/message.php');

$xml = new xml(dirname(__FILE__).'/schema/');
$result = $xml->parseXML(html_entity_decode(urldecode($_POST['message'])));

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
		case 'codexUpload':
			$codexHandler = new codexInputHandler();
			if ($codexHandler->import($xml->getData()))
				echo message::createResponse($xml->getMessageID());
			else
				echo message::createResponse($xml->getMessageID(), 'alreadyExisting');
			break;
		case 'getGameList':
			$gameHandler = new gameOutputHandler();
			$data = $xml->getData();
			echo message::createGameListResponse($xml->getMessageID(), $gameHandler->getList($data['versions'] == 'getAll'));
			break;
		case 'getCodexList':
			$codexHandler = new codexOutputHandler();
			$data = $xml->getData();
			echo message::createCodexListResponse($xml->getMessageID(), $codexHandler->getList($data['versions'] == 'getAll'));
			break;
		case 'getGame':
			$gameHandler = new gameOutputHandler();
			$data = $xml->getData();

			if ($data['version'])
				echo message::createGameResponse($xml->getMessageID(), $gameHandler->loadData($data['gameID'], $data['version']));
			else
				echo message::createGameResponse($xml->getMessageID(), $gameHandler->loadData($data['gameID']));

			break;
		case 'getCodex':
			$codexHandler = new codexOutputHandler();
			$data = $xml->getData();

			if ($data['version'])
				echo message::createCodexResponse($xml->getMessageID(), $codexHandler->loadData($data['codexID'], $data['version']));
			else
				echo message::createCodexResponse($xml->getMessageID(), $codexHandler->loadData($data['codexID']));

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