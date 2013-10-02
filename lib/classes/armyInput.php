<?php
require_once(dirname(__FILE__).'/models/army.php');

/**
 * handles all input for armies
 *
 * @author Neithan
 */
class ArmyInputHandler
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
		$this->gameModel = new GameModel(
			$this->data['gameID'], $this->data['gameVersion'], $this->data['gameEdition']
		);

		$this->armyModel = new ArmyModel(
			$this->gameModel->getGameId(),
			array(
				$this->data['armyID'],
				$this->data['version'],
				$this->data['edition']
			)
		);
	}

		/**
	 * check whether the army exists or not
	 *
	 * @return boolean
	 */
	private function checkExisting()
	{
		if ($this->armyModel->getArmyId())
			return true;
		else
			return false;
	}

	/**
	 * import a new army
	 *
	 * @author Neithan	 * @param array $data
	 * @return boolean
	 */
	public function import($data)
	{
		if (!$this->checkExisting())
		{
			$this->armyModel->saveArmy();

			return true;
		}
		else
			return false;
	}

	/**
	 * get the army data
	 *
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}
}