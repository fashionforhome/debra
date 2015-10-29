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

namespace Debra\Model;

use Debra\Model\Adapter\GitAdapter;
use Debra\Model\Adapter\JiraAdapter;
use Silex\Application;

/**
 * Class AbstractModel
 * @package Debra\Model
 */
abstract class AbstractModel extends AbstractObject
{
	/**
	 * @var mixed[]
	 */
	protected $data = array();

	/**
	 * initializes the model with data
	 *
	 * @param array $data
	 * @return $this
	 */
	public function load($data = array())
	{
		$this->data = array_merge($this->data, $data);

		return $this;
	}

	/**
	 * @param $key
	 * @param null $default
	 * @return null
	 */
	public function getData($key, $default = null)
	{
		return isset($this->data[$key]) ? $this->data[$key] : $default;
	}

	/**
	 * @param $key
	 * @param $value
	 * @return $this
	 */
	public function setData($key, $value)
	{
		$this->data[$key] = $value;

		return $this;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return $this->data;
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return serialize($this->data);
	}

	/**
	 * @param $string
	 * @return $this
	 */
	public function fromString($string)
	{
		$this->data = unserialize($string);

		return $this;
	}
}