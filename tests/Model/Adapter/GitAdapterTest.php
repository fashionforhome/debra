<?php
/**
 * This file is part of Debra.
 *
 * @category developer tool
 * @package debra-tests
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
/**
 * Class GitAdapterTest
 */
class GitAdapterTest extends PHPUnit_Framework_TestCase
{
	/**
	 * tests the execution of the deletion
	 */
	public function testDeleteRemoteBranches()
	{
		$expectedCmd = implode(';', array(
			'rm -r my-repo-path',
			'mkdir my-repo-path',
			'cd my-repo-path',
			'git-cmd init',
			'git-cmd remote add origin my-remote-url',
			'git-cmd push origin --delete branch-1 branch-2 branch-3'
		));

		$gitParserMock = $this->getMockBuilder('\\Debra\\Model\\Parser\\GitParser')
			->getMock();

		$adapter = $this->getMockBuilder('\\Debra\\Model\\Adapter\\GitAdapter')
			->setConstructorArgs(array($gitParserMock, 'my-remote-url', 'user', 'pw', 'my-repo-path', 'git-cmd'))
			->setMethods(array('run'))
			->getMock();

		$adapter->expects($this->once())
			->method('run')
			->with($this->equalTo($expectedCmd));

		$adapter->deleteRemoteBranches(array(
			'branch-1', 'branch-2', 'branch-3'
		));
	}

	/**
	 * tests whether the branch list is delegated to the parser correctly
	 */
	public function testExtractIssueKeys()
	{
		$returnKeys = array('a' => 1, 'b' => 2);
		$branchList = array('b-1', 'b-2');

		$gitParserMock = $this->getMockBuilder('\\Debra\\Model\\Parser\\GitParser')
			->setMethods(array('extractIssueKeys'))
			->getMock();

		$gitParserMock->expects($this->once())
			->method('extractIssueKeys')
			->with($this->equalTo($branchList))
			->will($this->returnValue($returnKeys));

		$adapter = new Debra\Model\Adapter\GitAdapter($gitParserMock, 'my-remote-url', 'user', 'pw', 'my-repo-path', 'git-cmd');

		$this->assertEquals($adapter->extractIssueKeys($branchList), $returnKeys);
	}

	/**
	 * tests the remote branches fetching
	 */
	public function testGetRemoteBranches()
	{
		$branches = array('DMF-1', 'DMF-22', 'some-branch', 'DMF-333');
		$remoteResponse = "hash_bla_bla\trefs/heads/DMF-1\n"
			. "hash_bla_bla\trefs/heads/DMF-22\n"
			. "hash_bla_bla\trefs/heads/some-branch\n"
			. "hash_bla_bla\trefs/heads/HEAD\n"
			. "hash_bla_bla\trefs/heads/DMF-333\n"
			. "some meta data";

		$gitParserMock = $this->getMockBuilder('\\Debra\\Model\\Parser\\GitParser')
			->getMock();

		$adapter = $this->getMockBuilder('\\Debra\\Model\\Adapter\\GitAdapter')
			->setConstructorArgs(array($gitParserMock, 'my-remote-url', 'user', 'pw', 'my-repo-path', 'git-cmd'))
			->setMethods(array('run'))
			->getMock();

		$adapter->expects($this->once())
			->method('run')
			->with($this->equalTo("git-cmd ls-remote my-remote-url"))
			->will($this->returnValue($remoteResponse));

		$this->assertEquals($branches, $adapter->getRemoteBranches());
	}
}