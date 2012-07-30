<?php
require_once(dirname(__FILE__).'/mysql.php');

class gameInputHandler
{
	private $data;

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
					xml = '.sqlval($this->data['gameXML']).'
			';
			query($sql);

			return true;
		}
		else
			return false;
	}

	public function loadData($gameID, $version = 'latest')
	{
		$sql = '
			SELECT * FROM games
			WHERE internal_id = '.sqlval($gameID).'
		';

		if ($version != 'latest')
			$sql .= 'AND version = '.sqlval($this->data['version']).'';
		else
			$sql .= 'AND version = (SELECT MAX(version) FROM games WHERE internal_id = '.sqlval ($gameID).')';

		$data = query($sql);

		$this->data = array(
			'name' => $data['name'],
			'version' => $data['verion'],
			'edition' => $data['edition'],
			'gameID' => $data['internal_id'],
			'creator' => $data['creator'],
			'createDateTime' => DateTime::createFromFormat('Y-m-d H:i:s', $data['create_datetime']),
			'gameXML' => $data['xml'],
		);
	}

	public function getData()
	{
		return $this->data;
	}
}