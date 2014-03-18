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
	 * @var \Display\Model\ConfigurationList
	 */
	private $configurationList;

	/**
	 * Create a new configuration output handler and load all configurations.
	 */
	function __construct()
	{
		$this->configurationList = new \Display\Model\ConfigurationList();
	}

	/**
	 * Get the given configuration value.
	 *
	 * @param string $key
	 * @return string
	 * @throws \WS\TatooSoapFault
	 */
	public function getValue($key)
	{
		$value = $this->configurationList->getValue($key);

		if (empty($value))
			throw new \WS\TatooSoapFault(
				\WS\TatooSoapFault::MISSINGCONFIGURATION,
				'Could not find configuration for key "' . $key . '".'
			);

		return $value;
	}
}