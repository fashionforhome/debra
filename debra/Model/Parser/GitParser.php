<?php namespace Debra\Model\Parser;

/**
 * parses the return of the git cli calls
 *
 * Class GitParser
 * @package Debra\Model\Parser
 */
class GitParser
{
	/**
	 * @var string
	 */
	const KEY_PREFIX = 'DMF-';

	/**
	 * gets a list of branches and returns all jira issue keys in an array
	 *
	 * @param string[] $branchList
	 * @return array
	 */
	public function extractIssueKeys($branchList)
	{
		$issues = array();
		foreach ($branchList as $branchName) {
			if (preg_match("/([0-9]+)$/i", $branchName, $match)) {
				$issues[$branchName] = static::KEY_PREFIX . $match[1];
			}
		}

		return $issues;
	}
}