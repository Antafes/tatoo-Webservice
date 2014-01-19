<?php
namespace WS;

/**
 * Handles the WSDLs
 *
 * @author Neithan
 */
class WsdlHandler
{
	/**
	 * List of available versions
	 *
	 * @var array
	 */
	private static $versionList = array(
		'1' => 'v1',
	);

	/**
	 * Check if the given version exists
	 *
	 * @param string $version The version to check
	 *
	 * @return boolean True if the version exists, otherwise false
	 */
	public static function check($version)
	{
		return array_key_exists($version, self::$versionList);
	}

	/**
	 * Get the WSDL file name for the given version
	 *
	 * @param string $version The version to get the wsdl for
	 *
	 * @return boolean|string The WSDL name on success, otherwise false
	 */
	public static function getWsdl($version)
	{
		if (self::check($version))
		{
			return self::$versionList[$version];
		}
		else
		{
			return self::$versionList[max(array_keys(self::$versionList))];
		}

		return false;
	}
}