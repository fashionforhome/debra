<?php

/**
 * Class IssueTest
 */
class IssueTest extends PHPUnit_Framework_TestCase
{
	/**
	 * test the to array transformation of the issue model
	 */
	public function testToArray()
	{
		$app = $this->getMockBuilder('\\Silex\\Application')
			->disableOriginalConstructor()
			->getMock();

		$date = new DateTime;
		$issue = new \Debra\Model\Entity\Issue($app);
		$issue->load(array(
			'key' => 'bar',
			'branch' => 'foo',
			'date' => $date
		));

		$expectedOutput = array(
			'id'            => '',
			'key'           => 'bar',
			'status'        => '',
			'summary'       => '',
			'last_updated'  => '',
			'url'           => '',
			'fixed_version' => '',
			'sprint'        => '',
			'branch'        => 'foo',
			'date'          => $date->format('m.d.Y H:i:s')
		);

		$this->assertEquals($expectedOutput, $issue->toArray());
	}
}