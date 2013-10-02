<?php
require_once(dirname(__FILE__).'/../../mysql.php');
require_once(dirname(__FILE__).'/../model.php');

/**
 * Model for the armies
 *
 * @author Neithan
 */
class ArmyModel extends Model
{
	/**
	 * @var int
	 */
	protected $armyId;

	/**
	 * @param string $gameId
	 * @param array  $data
	 */
	function __construct($gameId, $data)
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

		$this->getArmy($gameId, $data['armyID'], $version, $edition);
	}

	/**
	 *
	 * @param string     $gameId
	 * @param string     $internalId
	 * @param int|string $version
	 * @param int|string $edition
	 */
	protected function getArmy($gameId, $internalId, $version, $edition)
	{
		$editionWhere = '';
		if ($edition == 'max')
		{
			$editionWhere = '(
				SELECT MAX(sa.edition)
				FROM armies AS sa
				JOIN games AS sg
				WHERE sa.internal_id = '.sqlval($internalId).'
					AND !sa.deleted
					AND sg.game_id = '.sqlval($gameId).'
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
				SELECT MAX(sa.version)
				FROM armies AS sa
				JOIN games AS sg
				WHERE sa.internal_id = '.sqlval($internalId).'
					AND sa.edition = '.$editionWhere.'
					AND !sa.deleted
					AND sg.game_id = '.sqlval($gameId).'
			)';
		}
		else
		{
			$versionWhere = sqlval($version);
		}

		$sql = '
			SELECT
				a.army_id,
				a.game_id,
				a.`name`,
				a.version,
				a.edition,
				a.internal_id,
				a.creator,
				a.create_datetime,
				a.xml
			FROM armies AS a
			JOIN games AS g USING (game_id)
			WHERE a.internal_id = '.sqlval($internalId).'
				AND a.version = '.$versionWhere.'
				AND a.edition = '.$editionWhere.'
				AND !a.deleted
				AND g.game_id = '.sqlval($gameId).'
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
		$this->armyId         = intval($data['army_id']);
		$this->gameId         = intval($data['game_id']);
		$this->name           = $data['name'];
		$this->version        = intval($data['version']);
		$this->edition        = intval($data['edition']);
		$this->internalId     = $data['armyID'] ? $data['armyID'] : $data['internal_id'];
		$this->creator        = $data['creator'];
		$this->createDatetime = $data['createDateTime'] ? $data['createDateTime']
			: DateTime::createFromFormat('Y-m-d H:i:s', $data['create_datetime']);
		$this->xml            = $data['armyData'] ? $data['armyData'] : $data['xml'];
	}

	/**
	 * Save the army to the database
	 */
	public function saveArmy()
	{
		$sql = '
			INSERT INTO armies
			SET game_id = '.sqlval($this->gameId).',
				name = '.sqlval($this->data['name']).',
				version = '.sqlval($this->data['version']).',
				edition = '.sqlval($this->data['edition']).',
				internal_id = '.sqlval($this->data['armyID']).',
				creator = '.sqlval($this->data['creator']).',
				create_datetime = '.sqlval($this->data['createDateTime']->format('Y-m-d H:i:s')).',
				xml = '.sqlval($this->data['xml']).'
		';
		query($sql);
	}

	/**
	 * @return int
	 */
	public function getArmyId()
	{
		return $this->armyId;
	}

	/**
	 * Get a list of all available armies for the given game.
	 *
	 * @param string $gameID
	 * @param string $gameVersion
	 * @param string $gameEdition
	 * @param boolean $version
	 * @return array
	 */
	public static function getArmyList($gameID, $gameVersion, $gameEdition, $version = null)
	{
		$sql = '
			SELECT
				a.name,
				a.version,
				a.edition,
				a.internal_id AS armyID,
				a.creator
			FROM armies AS a
			JOIN games AS g USING (game_id)
			WHERE g.internal_id = '.sqlval($gameID).'
				AND g.version = '.sqlval($gameVersion).'
				AND g.edition = '.sqlval($gameEdition).'
		';

		if (!$version)
			$sql .= 'AND a.version = (
				SELECT MAX(i.version)
				FROM armies AS i
				JOIN games AS gi USING (game_id)
				WHERE i.internal_id = a.internal_id
					AND gi.version = g.version
					AND gi.edition = g.edition
			)';

		return query($sql, true);
	}
}