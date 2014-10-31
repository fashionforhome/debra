<?php namespace Debra\Model\Adapter;
use Debra\Model\Parser\GitParser;

/**
 * git remote repository adapter
 *
 * Class GitAdapter
 * @package Debra\Model\Adapter
 */
class GitAdapter
{
	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var string
	 */
	private $password;

	/**
	 * @var string
	 */
	private $repoPath;

	/**
	 * @var string
	 */
	private $gitPath;

	/**
	 * @var \Debra\Model\Parser\GitParser
	 */
	private $parser;

	/**
	 * @param \Debra\Model\Parser\GitParser $parser
	 * @param string $url
	 * @param string $username
	 * @param string $password
	 * @param string $repoPath
	 * @param string $gitPath
	 */
	public function __construct(GitParser $parser, $url, $username, $password, $repoPath, $gitPath = 'git')
	{
		$this->url = $url;
		$this->username = $username;
		$this->password = $password;
		$this->repoPath = $repoPath;
		$this->gitPath = $gitPath;
		$this->parser = $parser;
	}

	/**
	 * delete remote branches
	 *
	 * @param string[] $branches
	 */
	public function deleteRemoteBranches($branches)
	{
		$this->run(implode(";", array(
			'rm -r ' . $this->repoPath,
			'mkdir ' . $this->repoPath,
			'cd ' . $this->repoPath,
			$this->git('init'),
			$this->git('remote add origin ' . $this->url),
			$this->git('push origin --delete ' . implode(" ", $branches))
		)));
	}

	/**
	 * gets a list of branches and returns all jira issue keys in an array
	 *
	 * @param string[] $branchList
	 * @return array
	 */
	public function extractIssueKeys($branchList)
	{
		return $this->parser->extractIssueKeys($branchList);
	}

	/**
	 * returns an array of remote branches
	 *
	 * @return string[]
	 */
	public function getRemoteBranches()
	{
		$branches = array();
		$outputLines = explode("\n", $this->run($this->git('ls-remote ' . $this->url)));
		array_pop($outputLines);

		foreach ($outputLines as $line) {
			$parts = explode("\t", $line);
			$branchName = str_replace("refs/heads/", "", $parts[1]);
			if ($branchName !== 'HEAD') {
				$branches[] = $branchName;
			}
		}

		return $branches;
	}

	/**
	 * create a git command string
	 *
	 * @param string $cmd
	 * @return string
	 */
	private function git($cmd)
	{
		return $this->gitPath . ' ' . $cmd;
	}

	/**
	 * @param string $cmd
	 * @return string
	 */
	protected function run($cmd)
	{
		return shell_exec($cmd);
	}
}