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
			->setMethods(array('getData'))
			->getMock();

		$issue->expects($this->once())
			->method('getData')
			->with($this->equalTo('branch'))
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