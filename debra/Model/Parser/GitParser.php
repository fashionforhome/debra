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
namespace Debra\Model\Parser;

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