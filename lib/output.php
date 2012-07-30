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
				WHERE i.game_id = o.game_id
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

		$data = query($sql);

		return $data;
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
				WHERE i.codex_id = o.codex_id
			)';

		return query($sql, true);
	}
}