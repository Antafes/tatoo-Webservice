<?php
require_once(dirname(__FILE__).'/../../mysql.php');
require_once(dirname(__FILE__).'/../model.php');

/**
 * Model for the games
 *
 * @author Neithan
 */
class GameModel extends Model
{
	/**
	 * @param array $data
	 */
	function __construct($data)
	{
		$version = $data['version'];
		if (!$version)
		{
			$version = 'max';
		}

		$edition = $data['edition'];
		if (!$edition)
		{
			$edition = 'max';
		}

		$this->getGame($data['gameID'], $version, $edition);

		if (!$this->gameId)
		{
			$this->load($data);
		}
	}

	/**
	 * Get the game data
	 *
	 * @param int        $internalId The game id
	 * @param int|string $version    The tatoo version of the game or 'max' for the current maximum versio
	 * @param int|string $edition    The Games Workshop edition of the game or 'max' for the current maximum edition
	 */
	protected function getGame($internalId, $version, $edition)
	{
		$editionWhere = '';
		if ($edition == 'max')
		{
			$editionWhere = '(
				SELECT MAX(edition)
				FROM games
				WHERE internal_id = '.sqlval($internalId).'
					AND !deleted
			)';
		}
		else
		{
			$editionWhere = sqlval($edition);
		}

		$versionWhere = '';
		if ($version == 'max')
		{
			$versionWhere = '(
				SELECT MAX(version)
				FROM games
				WHERE internal_id = '.sqlval($internalId).'
					AND edition = '.$editionWhere.'
					AND !deleted
			)';
		}
		else
		{
			$versionWhere = sqlval($version);
		}

		$sql = '
			SELECT
				game_id,
				name,
				version,
				edition,
				internal_id,
				creator,
				create_datetime,
				xml
			FROM games
			WHERE internal_id = '.sqlval($internalId).'
				AND version = '.$versionWhere.'
				AND edition = '.$editionWhere.'
				AND !deleted
		';
		$data = query($sql);

		if ($data)
		{
			$this->load($data);
		}
	}

	/**
	 * @param array $data
	 */
	protected function load($data)
	{
		$this->gameId         = intval($data['game_id']);
		$this->name           = $data['name'];
		$this->version        = intval($data['version']);
		$this->edition        = intval($data['edition']);
		$this->internalId     = $data['gameID'] ? $data['gameID'] : $data['internal_id'];
		$this->creator        = $data['creator'];
		$this->createDatetime = $data['createDateTime'] ? $data['createDateTime']
			: DateTime::createFromFormat('Y-m-d H:i:s', $data['create_datetime']);
		$this->xml            = $data['gameData'] ? $data['gameData'] : $data['xml'];
	}

	/**
	 * Save the game data to the database
	 */
	public function saveGame()
	{
		$sql = '
			INSERT INTO games
			SET name = '.sqlval($this->name).',
				version = '.sqlval($this->version).',
				edition = '.sqlval($this->edition).',
				internal_id = '.sqlval($this->internalId).',
				creator = '.sqlval($this->creator).',
				create_datetime = '.sqlval($this->createDatetime->format('Y-m-d H:i:s')).',
				xml = '.sqlval($this->xml).'
		';
		query($sql);
	}

	/**
	 * Get a list with all available games.
	 *
	 * @param boolean $version
	 * @return array
	 */
	public static function getGameList($version = null)
	{
		$sql = '
			SELECT
				name,
				version,
				edition,
				internal_id AS gameID,
				creator
			FROM games AS o
		';

		if (!$version)
		{
			$sql .= 'WHERE version = (
				SELECT MAX(i.version)
				FROM games AS i
				WHERE i.internal_id = o.internal_id
			)';
		}

		return query($sql, true);
	}
}