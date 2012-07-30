<?php
class xml
{
	private $type;
	private $messageID;
	private $data;
	private $xsdPath;

	function __construct($xsdPath)
	{
		$this->data = array();
		$this->xsdPath = $xsdPath;
	}

	public function parseXML($xmlString)
	{
		libxml_use_internal_errors(true);

		$simplexml = simplexml_load_string($xmlString);
		$errors = libxml_get_errors();

		if (!$errors)
		{
			$this->type = $simplexml->type;
			$this->messageID = $simplexml->messageID;

			switch ($this->type)
			{
				case 'gameUpload':
					$validator = new XMLReader();
					$validator->XML($xmlString, 'UTF_8');

					if ($validator->setSchema($this->xsdPath.'/game_upload.xsd'))
					{
						$this->data = array(
							'name' => $simplexml->name,
							'version' => $simplexml->version,
							'edition' => $simplexml->edition,
							'gameID' => $simplexml->gameID,
							'creator' => $simplexml->creator,
							'createDateTime' => DateTime::createFromFormat('Y-m-d\TH:i:s', $simplexml->createDateTime),
							'gameXML' => $simplexml->gameXML,
						);
						return 'ok';
					}
					else
						return 'validationFailed';

					break;
				case 'codexUpload':
					$validator = new XMLReader();
					$validator->XML($xmlString, 'UTF_8');

					if ($validator->setSchema($this->xsdPath.'/codex_upload.xsd'))
					{
						$this->data = array(
							'name' => $simplexml->name,
							'version' => $simplexml->version,
							'edition' => $simplexml->edition,
							'codexID' => $simplexml->codexID,
							'gameID' => $simplexml->gameID,
							'gameVersion' => $simplexml->gameVersion,
							'creator' => $simplexml->creator,
							'createDateTime' => DateTime::createFromFormat('Y-m-d\TH:i:s', $simplexml->createDateTime),
							'codexXML' => $simplexml->codexXML,
						);
						return 'ok';
					}
					else
						return 'validationFailed';

					break;
				default:
					return 'noValidType';
					break;
			}
		}
		else
		{
			foreach ($errors as $error)
			{
				if ($error->code == 76)
					return 'parsingFailed';
			}
		}
	}

	public function getData()
	{
		return $this->data;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getMessageID()
	{
		return $this->messageID;
	}
}