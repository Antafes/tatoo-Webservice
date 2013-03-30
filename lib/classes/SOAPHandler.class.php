<?php
require_once(dirname(__FILE__).'/input.class.php');
require_once(dirname(__FILE__).'/output.class.php');

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
	 * @author Neithan
	 * @param mixed $response
	 * @return mixed
	 * @throws SoapFault
	 */
	private function checkResponse($response)
	{
		if (!$response)
			throw new SoapFault('Client', 'something veeeery bad happened', 'wsdl.php');

		return $response;
	}

	/**
	 * get the current tatoo version
	 *
	 * @author Neithan
	 * @return string
	 */
	public function getTatooVersion()
	{
		$configurations = new configurationOutputHandler();

		return $configurations->getValue('tatooVersion');
	}

	/**
	 * handles the game upload
	 *
	 * @author Neithan
	 * @param string $name
	 * @param string $gameID
	 * @param int $version
	 * @param int $edition
	 * @param string $creator
	 * @param string $createDateTime
	 * @param string $gameData
	 * @return string
	 * @throws SoapFault
	 */
	public function gameUpload($name, $gameID, $version, $edition, $creator, $createDateTime, $gameData)
	{
		$response = '';
		$datetime = DateTime::createFromFormat('Y-m-d\TH:i:s', $createDateTime);
		$data = array(
			'name' => $name,
			'version' => $version,
			'edition' => $edition,
			'gameID' => $gameID,
			'creator' => $creator,
			'createDateTime' => $datetime,
			'xml' => $gameData,
		);
		$gameHandler = new GameInputHandler();
		$importResult = $gameHandler->import($data);

		if ($importResult)
			$response = 'imported';
		elseif ($importResult === false)
			$response = 'alreadyExisting';

		if ($response == 'alreadyExisting')
			throw new SoapFault('Client', 'already existing', 'wsdl.php');

		return $this->checkResponse($response);
	}

	/**
	 * handles the army upload
	 *
	 * @author Neithan
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
	 * @throws SoapFault
	 */
	public function armyUpload($name, $gameID, $gameVersion, $gameEdition, $armyID, $version, $edition, $creator, $createDateTime, $armyData)
	{
		$response = '';
		$datetime = DateTime::createFromFormat('Y-m-d\TH:i:s', $createDateTime);

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
		$armyHandler = new ArmyInputHandler();
		$importResult = $armyHandler->import($data);

		if ($importResult)
			$response = 'imported';
		elseif ($importResult === false)
			$response = 'alreadyExisting';

		if ($response == 'alreadyExisting')
			throw new SoapFault('Client', 'already existing', 'wsdl.php');

		return $this->checkResponse($response);
	}

	/**
	 * get the current list of games
	 *
	 * @author Neithan
	 * @param string|null $versions
	 * @return array
	 */
	public function getGameList($versions = null)
	{
		$gameHandler = new GameOutputHandler();
		$list = $gameHandler->getList(!!$versions);

		return $list;
	}

	/**
	 * get the current list of armies for the given game
	 *
	 * @author Neithan
	 * @param string $gameID
	 * @param int $gameVersion
	 * @param int $gameEdition
	 * @param string|null $versions
	 * @return array
	 */
	public function getArmyList($gameID, $gameVersion, $gameEdition, $versions = null)
	{
		$armyHandler = new ArmyOutputHandler();
		$list = $armyHandler->getList($gameID, $gameVersion, $gameEdition, !!$versions);

		return $list;
	}

	/**
	 * get the given game
	 *
	 * @author Neithan
	 * @param string $gameID
	 * @param int|null $version
	 * @param int|null $edition
	 * @return array
	 */
	public function getGame($gameID, $version = null, $edition = null)
	{
		$gameHandler = new GameOutputHandler();
		return $gameHandler->loadData($gameID, $version ? $version : 'latest', $edition ? $edition : 'latest');
	}

	/**
	 * get the given army
	 *
	 * @author Neithan
	 * @param string $armyID
	 * @param int|null $version
	 * @param int|null $edition
	 * @return array
	 */
	public function getArmy($armyID, $version = null, $edition = null)
	{
		$armyHandler = new ArmyOutputHandler();
		return $armyHandler->loadData($armyID, $version ? $version : 'latest', $edition ? $edition : 'latest');
	}
}