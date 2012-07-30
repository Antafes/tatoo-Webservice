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
}