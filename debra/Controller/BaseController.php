<?php namespace Debra\Controller;
use Silex\Application;

/**
 * base controller
 *
 * Class BaseController
 * @package Debra\Controller
 */
class BaseController
{
	/**
	 * @var Application|\Silex\Application
	 */
	protected $app;

	/**
	 * @param \Silex\Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
}