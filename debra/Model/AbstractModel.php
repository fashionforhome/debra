<?php namespace Debra\Model;

use Debra\Model\Adapter\GitAdapter;
use Debra\Model\Adapter\JiraAdapter;
use Silex\Application;

/**
 * Class AbstractModel
 * @package Debra\Model
 */
abstract class AbstractModel extends AbstractObject implements \Serializable
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
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * String representation of object
	 * @link http://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 */
	public function serialize()
	{
		return serialize($this->data);
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
		$this->data = unserialize($serialized);
	}
}