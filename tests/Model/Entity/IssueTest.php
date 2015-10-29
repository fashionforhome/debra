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