<?php namespace Debra\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Debra\Model\Entity\Issue;
use Debra\Model\Entity\IssueCollection;

/**
 * provides the models and collections
 *
 * Class ModelServiceProvider
 * @package Debra\ServiceProvider
 */
class ModelServiceProvider implements ServiceProviderInterface
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
		$app['model.issue'] = function(Application $app) {
			return new Issue($app);
		};

		$app['model.issue.collection'] = function(Application $app){
			return new IssueCollection($app, $app['git'], $app['jira']);
		};
	}
}