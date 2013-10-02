<?php
/**
 * handles all output for armies
 *
 * @author Neithan
 */
class ArmyOutputHandler
{
	/**
	 * @var GameModel
	 */
	private $gameModel;

	/**
	 * @var ArmyModel
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
		$this->gameModel = new GameModel(array(
			'gameID' => $this->data['gameID'],
			'gameVersion' => $this->data['gameVersion'],
			'gameEdition' => $this->data['gameEdition'],
		));

		$this->armyModel = new ArmyModel(
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
		return ArmyModel::getArmyList($gameID, $gameVersion, $gameEdition, $version);
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