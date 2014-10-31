<?php namespace Debra\ServiceProvider;

use Debra\Model\Parser\JiraParser;
use Guzzle\Http\Client;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Debra\Model\Adapter\JiraAdapter;

/**
 * provides the service adapter for jira restful api
 *
 * Class JiraServiceProdiver
 * @package Debra\ServiceProvider
 */
class JiraServiceProvider implements ServiceProviderInterface
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
		// jira parser
		$app['jira_parser'] = $app->share(function(Application $app){
			return new JiraParser($app['config']->get('jira.url'));
		});

		// register the jira adapter
		$app['jira'] = $app->share(function(Application $app) {
			return new JiraAdapter(
				new Client(),
				$app['jira_parser'],
				$app['config']->get('jira.url'),
				$app['config']->get('jira.username'),
				$app['config']->get('jira.password')
			);
		});
	}
}