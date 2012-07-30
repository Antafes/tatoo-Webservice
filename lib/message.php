<?php
require_once(dirname(__FILE__).'/xml.php');

class message
{
	static function createResponse($id, $status = 'successful', $originalMessage = null)
	{
		$xml = new XMLWriter();
		$xml->openMemory();
		$xml->setIndentString("\t");
		$xml->setIndent(true);

		$xml->startDocument('1.0', 'UTF-8');

		$xml->startElement('message');
		$xml->writeElement('type', 'response');
		$xml->writeElement('messageID', $id);
		$xml->writeElement('status', $status);

		if ($originalMessage)
			$xml->writeElement('originalMessage', htmlentities($originalMessage));

		$xml->endElement(); // end of message

		$xml->endDocument();

		return $xml->outputMemory();
	}

	static function createGameListResponse($messageID, $gameList)
	{
		$xml = new XMLWriter();
		$xml->openMemory();
		$xml->setIndentString("\t");
		$xml->setIndent(true);

		$xml->startDocument('1.0', 'UTF-8');

		$xml->startElement('message');
		$xml->writeElement('type', 'gameListResponse');
		$xml->writeElement('messageID', $messageID);
		$xml->startElement('games');

		foreach ($gameList as $game)
		{
			$xml->startElement('game');
			$xml->writeElement('name', $game['name']);
			$xml->writeElement('version', $game['version']);
			$xml->writeElement('edition', $game['edition']);
			$xml->writeElement('gameID', $game['gameID']);
			$xml->writeElement('creator', $game['creator']);
			$xml->endElement(); // end of game
		}

		$xml->endElement(); // end of games
		$xml->endElement(); // end of message

		$xml->endDocument();

		return $xml->outputMemory();
	}
}