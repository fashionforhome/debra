<?php namespace Debra\Helper;

/**
 * carrier of the application configuration
 *
 * Class ConfigHelper
 * @package Debra\Helper
 */
class ConfigHelper
{
	/**
	 * @var mixed[]
	 */
	private $config;

	/**
	 *
	 */
	public function __construct()
	{
		$this->config = include __DIR__ . '/../../configs/app.php';
	}

	/**
	 * get the config via the dot notation path
	 *
	 * @param string $path
	 * @param mixed $default
	 * @return mixed|null
	 */
	public function get($path, $default = null)
	{
		$config = $this->config;
		$parts = explode(".", $path);

		// loop through all parts
		foreach ($parts as $part) {

			// if no value existing, return the default
			if (!isset($config[$part])) {
				return $default;

			// if the path exists do deeper into it
			} else {
				$config = $config[$part];
			}

		}

		return $config;
	}
}