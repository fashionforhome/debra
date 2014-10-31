<?php

/**
 * Class IssueCollectionTest
 */
class IssueCollectionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * check whether the deletion of a issue collection is delegated correctly to the git adapter
	 */
	public function testDeleteBranches()
	{
		$branchList = array('branch-1', 'branch-2');

		$jiraAdapter = $this->getMockBuilder('\\Debra\\Model\\Adapter\\JiraAdapter')
			->disableOriginalConstructor()
			->getMock();

		$gitAdapter = $this->getMockBuilder('\\Debra\\Model\\Adapter\\GitAdapter')
			->disableOriginalConstructor()
			->setMethods(array('deleteRemoteBranches'))
			->getMock();

		$gitAdapter->expects($this->once())
			->method('deleteRemoteBranches')
			->with($this->equalTo($branchList));

		$issueCollection = new \Debra\Model\Entity\IssueCollection($this->createAppMock(), $gitAdapter, $jiraAdapter);
		$issueCollection->add($this->createIssueMock($branchList[0]));
		$issueCollection->add($this->createIssueMock($branchList[1]));

		$issueCollection->deleteBranches();
	}

	/**
	 * @param $branchName
	 * @return \Debra\Model\Entity\Issue
	 */
	protected function createIssueMock($branchName)
	{
		$issue = $this->getMockBuilder('\\Debra\\Model\\Entity\\Issue')
			->disableOriginalConstructor()
			->setMethods(array('getBranch'))
			->getMock();

		$issue->expects($this->once())
			->method('getBranch')
			->will($this->returnValue($branchName));

		return $issue;
	}

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject
	 */
	private function createAppMock()
	{
		return $this->getMockBuilder('\\Silex\\Application')
			->disableOriginalConstructor()
			->getMock();
	}
}