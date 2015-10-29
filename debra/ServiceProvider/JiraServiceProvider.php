<?php
/**
 * This file is part of Debra.
 *
 * @category developer tool
 * @package debra
 *
 * @author Eduard Bess <eduard.bess@fashionforhome.de>
 *
 * @copyright (c) 2015 by fashion4home GmbH <www.fashionforhome.de>
 * @license GPL-3.0
 * @license http://opensource.org/licenses/GPL-3.0 GNU GENERAL PUBLIC LICENSE
 *
 * @version 1.0.0
 *
 * Date: 29.10.2015
 * Time: 23:00
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Debra\ServiceProvider;

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