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

namespace Debra\Model\Adapter;
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