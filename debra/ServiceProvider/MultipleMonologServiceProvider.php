<?php namespace Debra\ServiceProvider;

use Monolog\Handler\StreamHandler;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * provides monolog logging in multiple files / locations
 *
 * Class CacheServiceProvider
 * @package Debra\ServiceProvider
 */
class MultipleMonologServiceProvider implements ServiceProviderInterface
{

	/**
	 * Registers services on the given app.
	 *
	 * This method should only be used to configure services and parameters.
	 * It should not get services.
	 *
	 * @param Application $app An Application instance
	 */
	public function register(Application $app)
	{
	}

	/**
	 * Bootstraps the application.
	 *
	 * This method is called after all services are registered
	 * and should be used for "dynamic" configuration (whenever
	 * a service must be requested).
	 */
	public function boot(Application $app)
	{
		$app['monolog.user'] = $app->share(function($app) {
			$log = new $app['monolog.logger.class']($app['config']->get('log.channel'));
			$handler = new StreamHandler($app['config']->get('log.logfile'), $app['config']->get('log.level'));
			$log->pushHandler($handler);

			return $log;
		});
	}
}