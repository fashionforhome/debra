<?php namespace Debra\Model;

use Debra\Model\Entity\Issue;
use Silex\Application;

/**
 * Class AbstractCollection
 * @package Debra\Model
 */
abstract class AbstractCollection extends AbstractObject implements \Countable, \Serializable
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
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * String representation of object
	 * @link http://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 */
	public function serialize()
	{
		$models = array();
		foreach ($this->models as $model) {
			$models[] = serialize($model);
		}

		return json_encode($models);
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Constructs the object
	 * @link http://php.net/manual/en/serializable.unserialize.php
	 * @param string $serialized <p>
	 * The string representation of the object.
	 * </p>
	 * @return void
	 */
	public function unserialize($serialized)
	{
		$this->models = array();
		$models = json_decode($serialized);
		foreach ($models as $model) {
			$this->models[] = unserialize($model);
		}
	}
}