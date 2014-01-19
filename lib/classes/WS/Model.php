<?php
namespace WS;

/**
 * Description of model
 *
 * @author Neithan
 */
abstract class Model
{
	/**
	 * @var int
	 */
	protected $gameId;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var int
	 */
	protected $version;

	/**
	 * @var int
	 */
	protected $edition;

	/**
	 * @var string
	 */
	protected $internalId;

	/**
	 * @var string
	 */
	protected $creator;

	/**
	 * @var \DateTime
	 */
	protected $createDatetime;

	/**
	 * @var string
	 */
	protected $xml;

	/**
	 * @return int
	 */
	public function getGameId()
	{
		return $this->gameId;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return int
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * @return int
	 */
	public function getEdition()
	{
		return $this->edition;
	}

	/**
	 * @return string
	 */
	public function getInternalId()
	{
		return $this->internalId;
	}

	/**
	 * @return string
	 */
	public function getCreator()
	{
		return $this->creator;
	}

	/**
	 * @return DateTime
	 */
	public function getCreateDatetime()
	{
		return $this->createDatetime;
	}

	/**
	 * @return string
	 */
	public function getXml()
	{
		return $this->xml;
	}

	/**
	 * @param array $data
	 */
	protected abstract function load($data);
}