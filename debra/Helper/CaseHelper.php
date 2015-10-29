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
 * Class CaseHelper
 * @package Debra\Helper
 */
class CaseHelper
{
	/**
	 * transforms a string to camel case
	 *
	 * @param string $string
	 * @return string
	 */
	public function toCamelCase($string)
	{
		$changed = false;
		$newString = '';
		for($i = 0; $i < strlen($string); $i++) {

			// if underscore, make the next latter to uppercase
			if ($string[$i] === '_') {
				$newString .= strtoupper($string[++$i]);
				$changed = true;
			} else {
				$newString .= $string[$i];
			}
		}

		return $changed === true ? $this->toCamelCase($newString) : $newString;
	}

	/**
	 * transforms a string to snake case
	 *
	 * @param string $string
	 * @return string
	 */
	public function toSnakeCase($string)
	{
		$newString = '';
		for($i = 0; $i < strlen($string); $i++) {

			// if uppercase
			if (ctype_upper($string[$i]) === true) {
				$newString .= '_';
			}

			$newString .= strtolower($string[$i]);
		}

		return $newString;
	}
}