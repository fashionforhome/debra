<?php namespace Debra\Model;

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