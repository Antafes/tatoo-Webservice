<?php
require_once(dirname(__FILE__).'/../mysql.php');

/**
 * handles all output for games
 *
 * @author Neithan
 */
class gameOutputHandler
{
	/**
	 * get the list of games
	 *
	 * @author Neithan
	 * @param string|null $versions
	 * @return array
	 */
	function getList($versions = null)
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

		if (!$versions)
		{
			$sql .= 'WHERE version = (
				SELECT MAX(i.version)
				FROM games AS i
				WHERE i.internal_id = o.internal_id
			)';
		}

		return query($sql, true);
	}

	/**
	 * get the given game
	 *
	 * @author Neithan
	 * @param string $gameID
	 * @param int|string $version
	 * @param int|string $edition
	 * @return array
	 */
	public function loadData($gameID, $version = 'latest', $edition = 'latest')
	{
		$sql = '
			SELECT xml FROM games
			WHERE internal_id = '.sqlval($gameID).'
		';

		if ($version != 'latest')
			$sql .= 'AND version = '.sqlval($version).'';
		else
			$sql .= 'AND version = (SELECT MAX(version) FROM games WHERE internal_id = '.sqlval ($gameID).')';

		if ($edition != 'latest')
			$sql .= ' AND edition = '.sqlval($edition).'';
		else
			$sql .= ' AND edition = (SELECT MAX(edition) FROM games WHERE internal_id = '.sqlval($gameID).')';

		return query($sql);
	}
}

/**
 * handles all output for codexes
 */
class codexOutputHandler
{
	/**
	 * get the list of codexes
	 *
	 * @author Neithan
	 * @param string $gameID
	 * @param int $gameVersion
	 * @param int $gameEdition
	 * @param string|null $versions
	 * @return array
	 */
	function getList($gameID, $gameVersion, $gameEdition, $versions = null)
	{
		$sql = '
			SELECT
				c.name,
				c.version,
				c.edition,
				c.internal_id AS codexID,
				c.creator
			FROM codices AS c
			JOIN games AS g USING (game_id)
			WHERE g.internal_id = '.sqlval($gameID).'
				AND g.version = '.sqlval($gameVersion).'
				AND g.edition = '.sqlval($gameEdition).'
		';

		if (!$versions)
			$sql .= 'AND c.version = (
				SELECT MAX(i.version)
				FROM codices AS i
				JOIN games AS gi USING (game_id)
				WHERE i.internal_id = c.internal_id
					AND gi.version = g.version
					AND gi.edition = g.edition
			)';

		return query($sql, true);
	}

	/**
	 * get the given codex
	 *
	 * @author Neithan
	 * @param string $codexID
	 * @param int|string $version
	 * @param int|string $edition
	 * @return array
	 */
	public function loadData($codexID, $version = 'latest', $edition = 'latest')
	{
		$sql = '
			SELECT xml FROM codices
			WHERE internal_id = '.sqlval($codexID).'
		';

		if ($version != 'latest')
			$sql .= ' AND version = '.sqlval($version).'';
		else
			$sql .= ' AND version = (SELECT MAX(version) FROM codices WHERE internal_id = '.sqlval ($codexID).')';

		if ($edition != 'latest')
			$sql .= ' AND edition = '.sqlval($edition).'';
		else
			$sql .= ' AND edition = (SELECT MAX(edition) FROM codices WHERE internal_id = '.sqlval($codexID).')';

		return query($sql);
	}
}

class configurationOutputHandler
{
	private $configurations;

	function __construct()
	{
		$sql = '
			SELECT
				`key`,
				`value`
			FROM configurations
		';
		$temp = query($sql, true);

		foreach ($temp as $le)
			$this->configurations[$le['key']] = $le['value'];
	}

	public function getValue($key)
	{
		return $this->configurations[$key];
	}
}