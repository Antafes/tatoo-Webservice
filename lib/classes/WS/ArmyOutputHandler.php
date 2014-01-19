<?php
namespace WS;

/**
 * handles all output for armies
 *
 * @author Neithan
 */
class ArmyOutputHandler
{
	/**
	 * @var Model\Game
	 */
	private $gameModel;

	/**
	 * @var Model\Army
	 */
	private $armyModel;

	/**
	 * contains the army data
	 * @var array
	 */
	private $data;

	/**
	 * @param array $data
	 */
	function __construct($data)
	{
		$this->data = $data;
		$this->gameModel = new Model\Game(
			array(
				'gameID' => $this->data['gameID'],
				'gameVersion' => $this->data['gameVersion'],
				'gameEdition' => $this->data['gameEdition'],
			)
		);

		$this->armyModel = new Model\Army(
			$this->gameModel->getGameId(),
			array(
				'armyID' => $this->data['armyID'],
				'version' => $this->data['version'],
				'edition' => $this->data['edition'],
			)
		);
	}

	/**
	 * get the list of armies
	 *
	 * @param string $gameID
	 * @param int $gameVersion
	 * @param int $gameEdition
	 * @param string|null $version
	 * @return array
	 */
	public static function getList($gameID, $gameVersion, $gameEdition, $version = null)
	{
		return Model\Army::getArmyList($gameID, $gameVersion, $gameEdition, $version);
	}

	/**
	 * get the given army
	 *
	 * @return string
	 */
	public function getData()
	{
		return $this->armyModel->getXml();
	}
}