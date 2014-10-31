<?php namespace Debra\ServiceProvider;

use Debra\Model\Adapter\GitAdapter;
use Debra\Model\Parser\GitParser;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * provides the git repository service
 *
 * Class GitServiceProvider
 * @package Debra\ServiceProvider
 */
class GitServiceProvider implements ServiceProviderInterface
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
		// git remote adapter
		$app['git'] = $app->share(function(Application $app) {
			return new GitAdapter(
				new GitParser,
				$app['config']->get('git.url'),
				$app['config']->get('git.username'),
				$app['config']->get('git.password'),
				$app['config']->get('git.git_repo_path'),
				$app['config']->get('git.git_path')
			);
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