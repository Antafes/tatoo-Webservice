<?php
namespace Display\Model;

/**
 * Contains a list of configurations
 *
 * @author Neithan
 */
class ConfigurationList
{
	/**
	 * List with all configurations
	 *
	 * @var array
	 */
	private $configurations;

	/**
	 * Create the configuration object and load all configurations.
	 */
	function __construct()
	{
		$this->loadConfigurations();
	}

	/**
	 * Load all available configurations.
	 */
	private function loadConfigurations()
	{
		$sql = '
			SELECT
				configuration_id,
				`key`,
				`value`
			FROM configurations
		';
		$temp = query($sql, true);

		foreach ($temp as $le)
			$this->configurations[$le['key']] = new Configuration(
				$le['configuration_id'], $le['key'], $le['value']
			);
	}

	/**
	 * Get the given configuration value.
	 *
	 * @param string $key
	 * @return string
	 */
	public function getValue($key)
	{
		return $this->configurations[$key];
	}

	/**
	 * Get the complete configuration list.
	 *
	 * @return array
	 */
	public function getConfigurations()
	{
		return $this->configurations;
	}
}
