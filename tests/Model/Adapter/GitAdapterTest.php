<?php

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