<?php namespace Debra\Model\Entity;

use Debra\Model\AbstractCollection;
use Debra\Model\Adapter\GitAdapter;
use Debra\Model\Adapter\JiraAdapter;
use Silex\Application;

/**
 * model collection for issues
 *
 * Class IssueCollection
 * @package Debra\Model
 */
class IssueCollection extends AbstractCollection
{
	/**
	 * @var GitAdapter
	 */
	protected $gitAdapter;

	/**
	 * @var JiraAdapter
	 */
	protected $jiraAdapter;

	/**
	 * @param \Silex\Application $app
	 * @param GitAdapter $gitAdapter
	 * @param JiraAdapter $jiraAdapter
	 */
	public function __construct(Application $app, GitAdapter $gitAdapter, JiraAdapter $jiraAdapter)
	{
		parent::__construct($app);

		$this->gitAdapter = $gitAdapter;
		$this->jiraAdapter = $jiraAdapter;
	}

	/**
	 * loads all available issues
	 *
	 * @return $this
	 */
	public function load()
	{
		// get list of branches
		$branchData = $this->gitAdapter->getRemoteBranches();

		// branch - issue key - pairs
		$branchIssueKeys = $this->gitAdapter->extractIssueKeys($branchData);

		// issue data
		$issueData = $this->jiraAdapter->getIssuesByKeys($branchIssueKeys);

		// create issue collection
		foreach ($branchData as $branchName) {

			// if there is a jira issue for given branch
			if (isset($branchIssueKeys[$branchName])) {
				$issueKey = $branchIssueKeys[$branchName];
				$issueData[$issueKey]['branch'] = $branchName;
				$this->add(
					$this->app['model.issue']->load($issueData[$issueKey])
				);

				// no matching issue found, just an empty model with branch name
			} else {
				$this->add(
					$this->app['model.issue']->load(array(
						'branch' => $branchName
					))
				);
			}

		}

		return $this;
	}

	/**
	 * deletes all branches connected to the issues in the collection
	 *
	 * @return $this
	 */
	public function deleteBranches()
	{
		// extract branch names
		$branches = array();
		foreach ($this->models as $model) {
			$branches[] = $model->getData('branch');
		}

		// delete branches if there are some
		if (count($branches) > 0) {
			$this->gitAdapter->deleteRemoteBranches($branches);
		}

		return $this;
	}

	/**
	 * @param $string
	 * @return $this
	 */
	public function fromString($string)
	{
		$modelStrings = unserialize($string);
		foreach ($modelStrings as $modelString) {
			$this->add($this->app['model.issue']->fromString($modelString));
		}

		return $this;
	}
}