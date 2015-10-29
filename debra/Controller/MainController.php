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

namespace Debra\Controller;

use Debra\Model\Entity\IssueCollection;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * main controller of the whole application which controls the flow
 *
 * Class MainController
 * @package Debra\Controller
 */
class MainController extends BaseController
{
	/**
	 * @param Application $app
	 */
	public function __construct(Application $app)
	{
		parent::__construct($app);

		// determine whether the user is logged in
		$app['twig']->addGlobal('logged_in', $app['session']->has('user'));

		// cache updated time
		if ($app['cache']->has('last_updated') === true) {
			$lastUpdate = $app['cache']->get('last_updated');
		} else {
			$lastUpdate = new \DateTime;
		}

		$nowDateTime = (new \DateTime);
		$cacheTimeOuted = $nowDateTime->sub(new \DateInterval("PT3H")) > $lastUpdate;

		$app['twig']->addGlobal('cache_last_updated', $lastUpdate->format('d.m.Y H:i:s'));
		$app['twig']->addGlobal('cache_timeouted', $cacheTimeOuted);
	}



	/**
	 * remove cached issue data and load new
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function getEmptySessionCache()
	{
		$this->emptyCache();
		$this->setAllIssues($this->getIssueCollection());

		return true;
	}

	/**
	 * show the login form
	 *
	 * @param Request $request
	 * @return mixed
	 */
	public function getLoginForm(Request $request)
	{
		return $this->app['twig']->render('login/form.twig', array(
			'username' => $request->get('username', '')
		));
	}

	/**
	 * log in the user with given credentials
	 *
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function postLogin(Request $request)
	{
		$username = (string) $request->get('username');
		$password = md5((string) $request->get('password'));

		// log in if credentials ok
		$userPassword = $this->app['config']->get('users.' . $username);
		if ($userPassword !== null && $userPassword === $password) {
			$this->app['session']->set('user', $username);

			// load the issue data and cache them
			$this->emptyCache();

			$this->setAllIssues($this->getIssueCollection());
		}

		return true;
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function getLogout()
	{
		$this->app['session']->remove('user');
		$this->emptyCache();

		return $this->app->redirect(
			$this->app['url_generator']->generate('login.form')
		);
	}

	/**
	 * show the overview of branches and jira issues
	 *
	 * @param Request $request
	 * @return mixed
	 */
	public function getOverview(Request $request)
	{
		$issueCollection = $this->getIssueCollection();

		// if there is a selection forced fetch the data from the session
		$selectedBranches = array();
		if ($request->get('old') == true) {
			$selectedBranches = $this->getSelectedBranches();
		}

		// save the fetched issues to session
		$this->setAllIssues($issueCollection);

		// get team overview for teams
		$stats = array();
		for ($i = 0; $i < $issueCollection->count(); $i++) {
			$issue = $issueCollection->at($i);
			$teamString = preg_replace("/(-[0-9]+)/mi", "", $issue->getData('branch'));
			if (isset($stats[$teamString]) === false) {
				$stats[$teamString] = array('name' => $teamString, 'count' => 0);
			}
			$stats[$teamString]['count']++;
		}

		// sort for 6 columns
		usort($stats, function($a, $b) {
				if (strcmp($a['name'], $b['name']) > 0) {
					return 1;
				} elseif (strcmp($a['name'], $b['name']) > 0) {
					return -1;
				}
				return 0;
		});

		$columns = 6;
		$statsSorted = array();
		for ($i = 0; $i < count($stats); $i++) {
			if (isset($statsSorted[$i%$columns]) === false) {
				$statsSorted[$i%$columns] = array();
			}
			$statsSorted[$i%$columns][] = $stats[$i];
		}

		// render the overview templte
		return $this->app['twig']->render('overview.twig', array(
			'issues'                => $issueCollection->toArray(),
			'selected_branches'     => $selectedBranches,
			'stats'                 => $statsSorted
		));
	}

	/**
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function postSaveSelection(Request $request)
	{
		$this->setSelectedBranches($request->get('issues'));

		return $this->app->redirect(
			$this->app['url_generator']->generate('confirmation')
		);
	}

	/**
	 * show the confirmation view
	 *
	 * @return mixed
	 */
	public function getConfirmation()
	{
		$selectedBranches = $this->getSelectedBranches();
		$issueCollection = $this->getAllIssues()->filter(array(
			'branch' => $selectedBranches
		));

		return $this->app['twig']->render('confirmation.twig', array(
			'issues' => $issueCollection->toArray()
		));
	}

	/**
	 * @return array[]
	 */
	private function getSelectedBranches()
	{
		return json_decode($this->app['cache']->get('issues_selection', json_encode(array())));
	}

	/**
	 * @param string[] $branches
	 * @return $this
	 */
	private function setSelectedBranches($branches)
	{
		$this->app['cache']->set('issues_selection', json_encode($branches));

		return $this;
	}

	/**
	 * @return IssueCollection
	 */
	private function getAllIssues()
	{
		return $this->app['model.issue.collection']->fromString(
			$this->app['cache']->get('issues_all')
		);
	}

	/**
	 * @param IssueCollection $collection
	 * @return $this
	 */
	private function setAllIssues(IssueCollection $collection)
	{
		$this->app['cache']->set('issues_all', $collection->toString());

		return $this;
	}

	/**
	 * @return IssueCollection
	 */
	private function getDeletedIssues()
	{
		return $this->app['model.issue.collection']->fromString(
			$this->app['cache']->get('issues_deleted')
		);
	}

	/**
	 * @param IssueCollection $collection
	 * @return $this
	 */
	private function setDeletedIssues(IssueCollection $collection)
	{
		$this->app['cache']->set('issues_deleted', $collection->toString());

		return $this;
	}

	/**
	 * delete selected branches
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteSelectedBranches()
	{
		// get issues of deleted branches
		$selectedBranches = $this->getSelectedBranches();
		$collection = $this->getAllIssues();

		// delete branches
		$collection->filter(array(
			'branch' => $selectedBranches
		))->deleteBranches();

		// log deletions
		$this->app['monolog.user']->addInfo($this->app['session']->get('user') . ' deleted branches: ' . implode(", ", $selectedBranches));

		// set deleted issues
		$this->setDeletedIssues($collection);

		// truncate the session cache so new branches und jira issues will be fetched
		$this->emptyCache();

		return $this->app->redirect(
			$this->app['url_generator']->generate('report')
		);
	}

	/**
	 * show report view about the deleted branches
	 *
	 * @return mixed
	 */
	public function getReport()
	{
		return $this->app['twig']->render('report.twig', array(
			'issues' => $this->getDeletedIssues()->toArray()
		));
	}

	/**
	 * fetches the issue collection
	 *
	 * @param bool $force
	 * @return IssueCollection
	 */
	protected function getIssueCollection($force = false)
	{
		if ($force === false && $this->app['cache']->has('issues_all')) {
			return $issueCollection = $this->getAllIssues();
		}

		// load issue collection
		$issueCollection = $this->app['model.issue.collection']->load();
		$this->app['cache']->set('last_updated', new \DateTime);

		return $issueCollection;
	}

	/**
	 * truncate the cache layer
	 */
	protected function emptyCache()
	{
		$this->app['cache']->remove('issues_all');
		$this->app['cache']->remove('issues_selection');
	}
}