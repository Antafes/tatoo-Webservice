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

	public function getData()
	{
		return $this->data;
	}
}

class codexInputHandler
{
	private $data;

	private function checkExisting()
	{
		$sql = '
			SELECT * FROM codices
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
				INSERT INTO codices
				SET game_id = (
						SELECT game_id FROM games
						WHERE internal_id = '.sqlval($this->data['gameID']).'
							AND version = '.sqlval($this->data['gameVersion']).'
					),
					name = '.sqlval($this->data['name']).',
					version = '.sqlval($this->data['version']).',
					edition = '.sqlval($this->data['edition']).',
					internal_id = '.sqlval($this->data['codexID']).',
					creator = '.sqlval($this->data['creator']).',
					create_datetime = '.sqlval($this->data['createDateTime']->format('Y-m-d H:i:s')).',
					xml = '.sqlval($this->data['codexXML']).'
			';
			query($sql);

			return true;
		}
		else
			return false;
	}

	public function getData()
	{
		return $this->data;
	}
}