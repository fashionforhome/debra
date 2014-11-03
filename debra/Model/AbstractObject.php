<?php namespace Debra\Model;

use Silex\Application;

/**
 * abstract model class
 *
 * Class AbstractObject
 * @package Debra\Model
 */
abstract class AbstractObject
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