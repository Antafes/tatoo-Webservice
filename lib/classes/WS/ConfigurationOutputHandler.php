<?php
namespace WS;

/**
 * Fetches the tatoo configurations
 *
 * @author Neithan
 */
class ConfigurationOutputHandler
{
	/**
	 * @var array
	 */
	private $configurations;

	/**
	 * Create a new configuration output handler and load all configurations.
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
				`key`,
				`value`
			FROM configurations
		';
		$temp = query($sql, true);

		foreach ($temp as $le)
			$this->configurations[$le['key']] = $le['value'];
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
}