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
 * parses the response of the jira endpoint to a clean uniform data set
 *
 * Class JiraParser
 * @package Debra\Model\Parser
 */
class JiraParser
{
	/**
	 * @var string
	 */
	private $url;

	/**
	 * @param string $url
	 */
	public function __construct($url)
	{
		$this->url = $url;
	}

	/**
	 * parses the jira response and returns clean data set
	 *
	 * @param mixed[] $data
	 * @return mixed[]
	 */
	public function parseJiraIssue($data)
	{
		$cleanData = array(
			'id'            => $data['id'],
			'key'           => $data['key'],
			'status'        => $data['fields']['status']['name'],
			'summary'       => $data['fields']['summary'],
			'last_updated'  => new \DateTime($data['fields']['updated']),
			'url'           => $this->url . 'browse/' . $data['key']
		);

		// concat the fixed versions
		$cleanData['fixed_version'] = array_reduce($data['fields']['fixVersions'], function($carry, $item) {
			if (empty($carry)) {
				return $item['name'];
			} else {
				return $carry . ', ' . $item['name'];
			}
		}, '');

		// extract sprint name
		if (preg_match("/,name=([^,]+),/mi", $data['fields']['customfield_10560'][0], $match)) {
			$cleanData['sprint'] = $match[1];
		} else {
			$cleanData['sprint'] = '';
		}

		return $cleanData;
	}
}