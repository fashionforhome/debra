<?php namespace Debra\Helper;

use Silex\Application;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * cache layer for branch and jira issue data
 *
 * Class CacheHelper
 * @package Debra\Helper
 */
class CacheHelper
{
	/**
	 * @var \Symfony\Component\HttpFoundation\Session\Session
	 */
	private $session;

	/**
	 * @param Session $session
	 */
	public function __construct(Session $session)
	{
		$this->session = $session;
	}

	/**
	 * @param string $key
	 * @param mixed $val
	 * @return $this
	 */
	public function set($key, $val)
	{
		$this->session->set($key, serialize($val));

		return $this;
	}

	/**
	 * @param string $key
	 * @param mixed|null $default
	 * @return mixed|null
	 */
	public function get($key, $default = null)
	{
		if ($this->session->has($key)) {
			return unserialize($this->session->get($key));
		}

		return $default;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function has($key)
	{
		return $this->session->has($key);
	}

	/**
	 * @param string $key
	 * @return $this
	 */
	public function remove($key)
	{
		$this->session->remove($key);

		return $this;
	}
}