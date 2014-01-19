<?php
namespace WS;

/**
 * handles all output for games
 *
 * @author Neithan
 */
class GameOutputHandler
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

		$this->gameModel = new Model\Game($this->data);
	}

	/**
	 * check whether the game exists or not.
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
	 * Get the list of games.
	 *
	 * @param string|null $version
	 * @return array
	 */
	public static function getList($version = null)
	{
		return Model\Game::getGameList($version);
	}

	/**
	 * Get the given game.
	 *
	 * @return array
	 */
	public function getData()
	{
		return $this->gameModel->getXml();
	}
}