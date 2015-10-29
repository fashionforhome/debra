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