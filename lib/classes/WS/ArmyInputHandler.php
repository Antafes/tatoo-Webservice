<?php
namespace WS;

/**
 * handles all input for armies
 *
 * @author Neithan
 */
class ArmyInputHandler
{
	/**
	 * @var Model\Army
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
			$this->data['gameID'], $this->data['gameVersion'], $this->data['gameEdition']
		);

		$this->armyModel = new Model\Army(
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
	public function import()
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