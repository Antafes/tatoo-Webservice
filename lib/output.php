<?php
require_once(dirname(__FILE__).'/mysql.php');

class gameOutputHandler
{
	function getList($getAll)
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

		if (!$getAll)
			$sql .= 'WHERE version = (
				SELECT MAX(i.version)
				FROM games AS i
				WHERE i.internal_id = o.internal_id
			)';

		return query($sql, true);
	}

	public function loadData($gameID, $version = 'latest')
	{
		$sql = '
			SELECT xml FROM games
			WHERE internal_id = '.sqlval($gameID).'
		';

		if ($version != 'latest')
			$sql .= 'AND version = '.sqlval($version).'';
		else
			$sql .= 'AND version = (SELECT MAX(version) FROM games WHERE internal_id = '.sqlval ($gameID).')';

		return query($sql);
	}
}

class codexOutputHandler
{
	function getList($getAll)
	{
		$sql = '
			SELECT
				name,
				version,
				edition,
				internal_id AS codexID,
				creator
			FROM codices AS o
		';

		if (!$getAll)
			$sql .= 'WHERE version = (
				SELECT MAX(i.version)
				FROM codices AS i
				WHERE i.internal_id = o.internal_id
			)';

		return query($sql, true);
	}

	public function loadData($codexID, $version = 'latest')
	{
		$sql = '
			SELECT xml FROM codices
			WHERE internal_id = '.sqlval($codexID).'
		';

		if ($version != 'latest')
			$sql .= 'AND version = '.sqlval($version).'';
		else
			$sql .= 'AND version = (SELECT MAX(version) FROM codices WHERE internal_id = '.sqlval ($codexID).')';

		return query($sql);
	}
}