<?php namespace Debra\Controller;

use Debra\Model\IssueCollection;
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
		$this->app['cache']->set('issues_all', $this->getIssueCollection());

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

			$this->app['cache']->set('issues_all', $this->getIssueCollection());
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
			$selectedBranches = $this->app['cache']->get('issues_selection', array());
		}

		// save the fetched issues to session
		$this->app['cache']->set('issues_all', $issueCollection);

		// render the overview templte
		return $this->app['twig']->render('overview.twig', array(
			'issues'                => $issueCollection->toArray(),
			'selected_branches'     => $selectedBranches
		));
	}

	/**
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function postSaveSelection(Request $request)
	{
		$this->app['cache']->set('issues_selection', $request->get('issues'));

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
		$selectedBranches = $this->app['cache']->get('issues_selection');
		$issueCollection = $this->app['cache']->get('issues_all')->setApp($this->app);

		$issueCollection->filter(array(
			'branch' => $selectedBranches
		));

		return $this->app['twig']->render('confirmation.twig', array(
			'issues' => $issueCollection->toArray()
		));
	}

	/**
	 * delete selected branches
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteSelectedBranches()
	{
		// get issues of deleted branches
		$selectedBranches = $this->app['cache']->get('issues_selection');
		$collection = $this->app['cache']->get('issues_all')->setApp($this->app);

		// delete branches
		$collection->filter(array(
			'branch' => $selectedBranches
		))->deleteBranches();

		// log deletions
		$this->app['monolog.user']->addInfo($this->app['session']->get('user') . ' deleted branches: ' . implode(", ", $selectedBranches));

		// set deleted issues
		$this->app['cache']->set('issues_deleted', $collection);

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
			'issues' => $this->app['cache']->get(
					'issues_deleted',
					$this->app['model.issue.collection']
			)->setApp($this->app)->toArray()
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
			return $issueCollection = $this->app['cache']->get('issues_all')->setApp($this->app);
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