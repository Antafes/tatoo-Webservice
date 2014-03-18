<?php
namespace Display\Model;

/**
 * Description of Configuration
 *
 * @author Neithan
 */
class Configuration
{
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var string
	 */
	private $key;

	/**
	 * @var string
	 */
	private $value;

	function __construct($id, $key, $value)
	{
		$this->id = $id;
		$this->key = $key;
		$this->value = $value;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getKey()
	{
		return $this->key;
	}

	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Create a new configuration object by id
	 *
	 * @param integer $id
	 * @return \self
	 */
	public static function loadById($id)
	{
		$sql = '
			SELECT
				configuration_id,
				`key`,
				`value`
			FROM configurations
			WHERE configuration_id = ' . sqlval($id) . '
		';
		$data = query($sql);

		return new self($data['configuration_id'], $data['key'], $data['value']);
	}

	/**
	 * Create a new configuration and return a configuration object. Returns false on error.
	 *
	 * @param string $key
	 * @param string $value
	 * @return \self|boolean
	 */
	public static function create($key, $value)
	{
		$sql = '
			INSERT INTO configurations
			SET `key` = ' . sqlval($key) . ',
				`value` = ' . sqlval($value) . '
		';
		$id = query($sql);

		if ($id)
			return self::loadById($id);
		else
			return false;
	}

	/**
	 * Update the configuration
	 *
	 * @param string $key
	 * @param string $value
	 * @return boolean
	 */
	public function update($key, $value)
	{
		$sql = '
			UPDATE configurations
			SET `key` = ' . sqlval($key) . ',
				`value` = ' . sqlval($value) . '
			WHERE configuration_id = ' . sqlval($this->id) . '
		';
		$result = query($sql);

		if ($result)
		{
			$this->key = $key;
			$this->value = $value;
			return true;
		}

		return false;
	}

	/**
	 * Remove a configuration
	 *
	 * @param integer $id
	 * @return boolean
	 */
	public static function delete($id)
	{
		$sql = '
			DELETE FROM configurations
			WHERE configuration_id = ' . sqlval($id) . '
		';
		return (bool) query($sql);
	}
}
