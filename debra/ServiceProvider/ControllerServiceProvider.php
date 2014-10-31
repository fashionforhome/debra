<?php namespace Debra\ServiceProvider;

use Debra\Controller\MainController;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * provides the controller service
 *
 * Class CacheServiceProvider
 * @package Debra\ServiceProvider
 */
class ControllerServiceProvider implements ServiceProviderInterface
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
		$app['controller.main'] = $app->share(function(Application $app) {
			return new MainController($app);
		});
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
	}
}