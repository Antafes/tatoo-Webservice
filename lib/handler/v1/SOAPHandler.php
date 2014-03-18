<?php

/**
 * This class handles the incoming and outgoing SOAP messages
 *
 * @author Neithan
 */
class SOAPHandler
{
	/**
	 * check if the response is set
	 *
	 * @param mixed $response
	 * @return mixed
	 * @throws \WS\TatooSoapFault
	 */
	private function checkResponse($response)
	{
		if (!$response)
			throw new \WS\TatooSoapFault(
				\WS\TatooSoapFault::NORESPONSE, 'something veeeery bad happened'
			);

		return $response;
	}

	/**
	 * get the current tatoo version
	 *
	 * @return string
	 */
	public function getTatooConfiguration($configuration)
	{
		$configurations = new \WS\configurationOutputHandler();

		return $configurations->getValue($configuration);
	}

	/**
	 * handles the game upload
	 *
	 * @param string $name
	 * @param string $gameID
	 * @param int $version
	 * @param int $edition
	 * @param string $creator
	 * @param string $createDateTime
	 * @param string $gameData
	 * @return string
	 * @throws \WS\TatooSoapFault
	 */
	public function gameUpload($name, $gameID, $version, $edition, $creator, $createDateTime, $gameData)
	{
		$response = '';
		$datetime = \DateTime::createFromFormat('Y-m-d\TH:i:s', $createDateTime);
		$data = array(
			'name' => $name,
			'version' => $version,
			'edition' => $edition,
			'gameID' => $gameID,
			'creator' => $creator,
			'createDateTime' => $datetime,
			'xml' => $gameData,
		);
		$gameHandler = new \WS\GameInputHandler($data);
		$importResult = $gameHandler->import();

		if ($importResult)
			$response = 'imported';
		elseif ($importResult === false)
			$response = 'alreadyExisting';

		if ($response == 'alreadyExisting')
			throw new \WS\TatooSoapFault(\WS\TatooSoapFault::ALREADYEXISTS, 'already existing');

		return $this->checkResponse($response);
	}

	/**
	 * handles the army upload
	 *
	 * @param string $name
	 * @param string $gameID
	 * @param int $gameVersion
	 * @param int $gameEdition
	 * @param string $armyID
	 * @param int $version
	 * @param int $edition
	 * @param string $creator
	 * @param string $createDateTime
	 * @param string $armyData
	 * @return string
	 * @throws \WS\TatooSoapFault
	 */
	public function armyUpload($name, $gameID, $gameVersion, $gameEdition, $armyID, $version, $edition, $creator, $createDateTime, $armyData)
	{
		$response = '';
		$datetime = \DateTime::createFromFormat('Y-m-d\TH:i:s', $createDateTime);

		$data = array(
			'name' => $name,
			'version' => $version,
			'edition' => $edition,
			'gameID' => $gameID,
			'gameVersion' => $gameVersion,
			'gameEdition' => $gameEdition,
			'armyID' => $armyID,
			'creator' => $creator,
			'createDateTime' => $datetime,
			'xml' => $armyData,
		);
		$armyHandler = new \WS\ArmyInputHandler($data);
		$importResult = $armyHandler->import();

		if ($importResult)
			$response = 'imported';
		elseif ($importResult === false)
			$response = 'alreadyExisting';

		if ($response == 'alreadyExisting')
		{
			throw new \WS\TatooSoapFault(\WS\TatooSoapFault::ALREADYEXISTS, 'already existing');
		}

		return $this->checkResponse($response);
	}

	/**
	 * get the current list of games
	 *
	 * @param string|null $versions
	 * @return array
	 */
	public function getGameList($versions = null)
	{
		return \WS\GameOutputHandler::getList(!!$versions);
	}

	/**
	 * get the current list of armies for the given game
	 *
	 * @param string $gameID
	 * @param int $gameVersion
	 * @param int $gameEdition
	 * @param string|null $versions
	 * @return array
	 */
	public function getArmyList($gameID, $gameVersion, $gameEdition, $versions = null)
	{
		return \WS\ArmyOutputHandler::getList($gameID, $gameVersion, $gameEdition, !!$versions);
	}

	/**
	 * get the given game
	 *
	 * @param string $gameID
	 * @param int|null $version
	 * @param int|null $edition
	 * @return array
	 */
	public function getGame($gameID, $version = null, $edition = null)
	{
		$gameHandler = new \WS\GameOutputHandler(array(
			'gameID' => $gameID,
			'version' => $version,
			'edition' => $edition,
		));

		return $gameHandler->getData();
	}

	/**
	 * get the given army
	 *
	 * @param string $armyID
	 * @param int|null $version
	 * @param int|null $edition
	 * @return array
	 */
	public function getArmy($gameID, $gameVersion, $gameEdition, $armyID, $version = null, $edition = null)
	{
		$armyHandler = new \WS\ArmyOutputHandler(
			array(
				'gameID' => $gameID,
				'gameVersion' => $gameVersion,
				'gameEdition' => $gameEdition,
				'armyID' => $armyID,
				'version' => $version,
				'edition' => $edition,
			)
		);

		return $armyHandler->getData();
	}
}