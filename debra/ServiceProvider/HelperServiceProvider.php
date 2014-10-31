<?php namespace Debra\ServiceProvider;

use Debra\Helper\CacheHelper;
use Debra\Helper\CaseHelper;
use Debra\Helper\ConfigHelper;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * configuration service provider for the app
 *
 * Class ConfigServiceProvider
 * @package Debra\ServiceProvider
 */
class HelperServiceProvider implements ServiceProviderInterface
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
		$app['config'] = $app->share(function(){
			return new ConfigHelper();
		});

		$app['case_helper'] = $app->share(function(){
			return new CaseHelper();
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
		$app['cache'] = $app->share(function($app){
			return new CacheHelper($app['session']);
		});
	}
}