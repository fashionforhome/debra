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

use Debra\Model\Entity\Issue;
use Silex\Application;

/**
 * Class AbstractCollection
 * @package Debra\Model
 */
abstract class AbstractCollection extends AbstractObject implements \Countable
{
	/**
	 * @var AbstractModel[]
	 */
	protected $models = array();

	/**
	 * loads all available issues
	 *
	 * @return $this
	 */
	abstract public function load();

	/**
	 * filters the collection
	 *
	 * @param mixed[] $filters
	 * @return $this
	 */
	public function filter($filters)
	{
		foreach ($filters as $name => $value) {
			$this->models = array_filter($this->models, function(AbstractModel $model) use ($name, $value) {

				// if array given, so multiple values to look for
				if (is_array($value)) {
					return in_array($model->getData($name), $value);
				}

				return $model->getData($name) === $value;

			});
		}

		return $this;
	}

	/**
	 * order the collection by given attributes
	 *
	 * @param mixed[] $orders
	 * @return $this
	 */
	public function order($orders)
	{
		$orders = array_reverse($orders);
		usort($this->models, function(AbstractModel $a, AbstractModel $b) use ($orders) {

			// loop through all given order attribute
			foreach ($orders as $name => $val) {

				// if string order alphabetically
				if (is_string($a->getData($name))) {

					if (strcmp($a->getData($name), $b->getData($name)) > 0) {
						return 1 * $val;
					} elseif (strcmp($a->getData($name), $b->getData($name)) < 0) {
						return -1 *$val;
					}

				} else {

					if ($a->getData($name) > $b->getData($name)) {
						return 1 * $val;
					} elseif ($a->getData($name) < $b->getData($name)) {
						return -1 *$val;
					}

				}
			}

			// if no difference in attributes
			return 0;

		});

		return $this;
	}

	/**
	 * adds an model to the collection
	 *
	 * @param AbstractModel $model
	 * @return $this
	 */
	public function add(AbstractModel $model)
	{
		$this->models[] = $model;

		return $this;
	}

	/**
	 * returns an issue for given position
	 *
	 * @param int $index
	 * @return Issue|null
	 */
	public function at($index)
	{
		return isset($this->models[$index]) ? $this->models[$index] : null;
	}

	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->models);
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		$result = array();
		foreach ($this->models as $model) {
			$result[] = $model->toArray();
		}

		return $result;
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		$models = array();
		foreach ($this->models as $model) {
			$models[] = $model->toString();
		}

		return serialize($models);
	}

	/**
	 * @param $string
	 * @return $this
	 */
	abstract public function fromString($string);
}