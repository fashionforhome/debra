<?php
/**
 * This file is part of Debra.
 *
 * @category developer tool
 * @package debra
 *
 * @author Eduard Bess <eduard.bess@fashionforhome.de>
 *
 * @copyright (c) 2015 by fashion4home GmbH <www.fashionforhome.de>
 * @license GPL-3.0
 * @license http://opensource.org/licenses/GPL-3.0 GNU GENERAL PUBLIC LICENSE
 *
 * @version 1.0.0
 *
 * Date: 29.10.2015
 * Time: 23:00
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace Debra\Helper;

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