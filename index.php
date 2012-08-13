<?php
require_once(dirname(__FILE__).'/lib/xml.php');
require_once(dirname(__FILE__).'/lib/input.php');
require_once(dirname(__FILE__).'/lib/output.php');
require_once(dirname(__FILE__).'/lib/message.php');

$GLOBALS['debug_file'] = fopen('log.txt', 'w+');
$xml = new xml(dirname(__FILE__).'/schema/');
$result = $xml->parseXML(html_entity_decode(urldecode($_POST['message'])));

$responseMessage = '';
if ($result == 'ok')
{
	switch ($xml->getType()) {
		case 'gameUpload':
			$gameHandler = new gameInputHandler();
			if ($gameHandler->import($xml->getData()))
			{
				$responseMessage = message::createResponse($xml->getMessageID());
			}
			else
			{
				$responseMessage = message::createResponse($xml->getMessageID(), 'alreadyExisting');
			}
			fwrite($GLOBALS['debug_file'], $responseMessage);
			break;
		case 'codexUpload':
			$codexHandler = new codexInputHandler();
			if ($codexHandler->import($xml->getData()))
				$responseMessage = message::createResponse($xml->getMessageID());
			else
				$responseMessage = message::createResponse($xml->getMessageID(), 'alreadyExisting');
			break;
		case 'getGameList':
			$gameHandler = new gameOutputHandler();
			$data = $xml->getData();
			$responseMessage = message::createGameListResponse($xml->getMessageID(), $gameHandler->getList($data['versions'] == 'getAll'));
			break;
		case 'getCodexList':
			$codexHandler = new codexOutputHandler();
			$data = $xml->getData();
			$responseMessage = message::createCodexListResponse($xml->getMessageID(), $codexHandler->getList($data['versions'] == 'getAll'));
			break;
		case 'getGame':
			$gameHandler = new gameOutputHandler();
			$data = $xml->getData();

			if ($data['version'])
				$responseMessage = message::createGameResponse($xml->getMessageID(), $gameHandler->loadData($data['gameID'], $data['version']));
			else
				$responseMessage = message::createGameResponse($xml->getMessageID(), $gameHandler->loadData($data['gameID']));

			break;
		case 'getCodex':
			$codexHandler = new codexOutputHandler();
			$data = $xml->getData();

			if ($data['version'])
				$responseMessage = message::createCodexResponse($xml->getMessageID(), $codexHandler->loadData($data['codexID'], $data['version']));
			else
				$responseMessage = message::createCodexResponse($xml->getMessageID(), $codexHandler->loadData($data['codexID']));

			break;
	}
}
else
{
	if ($result == 'parsingFailed')
		$responseMessage = message::createResponse($xml->getMessageID(), $result, $_POST['message']);
	else
		$responseMessage = message::createResponse($xml->getMessageID(), $result);
}

echo utf8_encode($responseMessage);

fclose($GLOBALS['debug_file']);