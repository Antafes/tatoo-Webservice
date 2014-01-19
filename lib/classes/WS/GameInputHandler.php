<?php
namespace WS;

/**
 * handles all input for games
 *
 * @author Neithan
 */
class GameInputHandler
{
	/**
	 * @var Model\Game
	 */
	private $gameModel;

	/**
	 * contains the game data
	 *
	 * @var array
	 */
	private $data;

	/**
	 * @param array $data
	 */
	function __construct($data)
	{
		$this->data = $data;

		$this->gameModel = new Model\Game($data);
	}

	/**
	 * check whether the game exists or not
	 *
	 * @return boolean
	 */
	private function checkExisting()
	{
		if ($this->gameModel->getGameId())
			return true;
		else
			return false;
	}

	/**
	 * import a new game
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function import()
	{
		if (!$this->checkExisting())
		{
			$this->gameModel->saveGame();

			return true;
		}
		else
			return false;
	}

	/**
	 * get the game data
	 *
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}
}