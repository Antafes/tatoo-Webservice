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

	static function createCodexListResponse($messageID, $codexList)
	{
		$xml = new XMLWriter();
		$xml->openMemory();
		$xml->setIndentString("\t");
		$xml->setIndent(true);

		$xml->startDocument('1.0', 'UTF-8');

		$xml->startElement('message');
		$xml->writeElement('type', 'codexListResponse');
		$xml->writeElement('messageID', $messageID);
		$xml->startElement('codices');

		foreach ($codexList as $codex)
		{
			$xml->startElement('codex');
			$xml->writeElement('name', $codex['name']);
			$xml->writeElement('version', $codex['version']);
			$xml->writeElement('edition', $codex['edition']);
			$xml->writeElement('codexID', $codex['codexID']);
			$xml->writeElement('creator', $codex['creator']);
			$xml->endElement(); // end of codex
		}

		$xml->endElement(); // end of codices
		$xml->endElement(); // end of message

		$xml->endDocument();

		return $xml->outputMemory();
	}

	static function createGameResponse($messageID, $xml)
	{
		$xml = new XMLWriter();
		$xml->openMemory();
		$xml->setIndentString("\t");
		$xml->setIndent(true);

		$xml->startDocument('1.0', 'UTF-8');

		$xml->startElement('message');
		$xml->writeElement('type', 'gameResponse');
		$xml->writeElement('messageID', $messageID);
		$xml->writeElement('xml', htmlentities($xml));
		$xml->endElement(); // end of message

		$xml->endDocument();

		return $xml->outputMemory();
	}

	static function createCodexResponse($messageID, $xml)
	{
		$xml = new XMLWriter();
		$xml->openMemory();
		$xml->setIndentString("\t");
		$xml->setIndent(true);

		$xml->startDocument('1.0', 'UTF-8');

		$xml->startElement('message');
		$xml->writeElement('type', 'gameResponse');
		$xml->writeElement('messageID', $messageID);
		$xml->writeElement('xml', htmlentities($xml));
		$xml->endElement(); // end of message

		$xml->endDocument();

		return $xml->outputMemory();
	}
}