<?php
require_once(dirname(__FILE__).'/../mysql.php');

/**
 * handles all input for games
 *
 * @author Neithan
 */
class GameInputHandler
{
	/**
	 * contains the game data
	 * @var array
	 */
	private $data;

	/**
	 * check whether the game exists or not
	 *
	 * @author Neithan
	 * @return boolean
	 */
	private function checkExisting()
	{
		$sql = '
			SELECT * FROM games
			WHERE internal_id = '.sqlval($this->data['gameID']).'
				AND version = '.sqlval($this->data['version']).'
		';
		$data = query($sql);

		if ($data)
			return true;
		else
			return false;
	}

	/**
	 * import a new game
	 *
	 * @author Neithan
	 * @param array $data
	 * @return boolean
	 */
	public function import($data)
	{
		$this->data = $data;

		if (!$this->checkExisting())
		{
			$sql = '
				INSERT INTO games
				SET name = '.sqlval($this->data['name']).',
					version = '.sqlval($this->data['version']).',
					edition = '.sqlval($this->data['edition']).',
					internal_id = '.sqlval($this->data['gameID']).',
					creator = '.sqlval($this->data['creator']).',
					create_datetime = '.sqlval($this->data['createDateTime']->format('Y-m-d H:i:s')).',
					xml = '.sqlval($this->data['xml']).'
			';
			query($sql);

			return true;
		}
		else
			return false;
	}

	/**
	 * get the game data
	 *
	 * @author Neithan
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}
}

/**
 * handles all input for armies
 *
 * @author Neithan
 */
class ArmyInputHandler
{
	/**
	 * contains the army data
	 * @var array
	 */
	private $data;

	/**
	 * check whether the army exists or not
	 *
	 * @author Neithan
	 * @return boolean
	 */
	private function checkExisting()
	{
		$sql = '
			SELECT c.*
			FROM codices AS c
			JOIN games AS g USING (game_id)
			WHERE c.internal_id = '.sqlval($this->data['armyID']).'
				AND c.version = '.sqlval($this->data['version']).'
				AND c.edition = '.sqlval($this->data['edition']).'
				AND g.internal_id = '.sqlval($this->data['gameID']).'
				AND g.version = '.sqlval($this->data['gameVersion']).'
				AND g.edition = '.sqlval($this->data['gameEdition']).'
		';
		$data = query($sql);

		if ($data)
			return true;
		else
			return false;
	}

	/**
	 * import a new army
	 *
	 * @author Neithan
	 * @param array $data
	 * @return boolean
	 */
	public function import($data)
	{
		$this->data = $data;

		if (!$this->checkExisting())
		{
			$sql = '
				INSERT INTO codices
				SET game_id = (
						SELECT game_id FROM games
						WHERE internal_id = '.sqlval($this->data['gameID']).'
							AND version = '.sqlval($this->data['gameVersion']).'
					),
					name = '.sqlval($this->data['name']).',
					version = '.sqlval($this->data['version']).',
					edition = '.sqlval($this->data['edition']).',
					internal_id = '.sqlval($this->data['armyID']).',
					creator = '.sqlval($this->data['creator']).',
					create_datetime = '.sqlval($this->data['createDateTime']->format('Y-m-d H:i:s')).',
					xml = '.sqlval($this->data['xml']).'
			';
			query($sql);

			return true;
		}
		else
			return false;
	}

	/**
	 * get the army data
	 *
	 * @author Neithan
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}
}