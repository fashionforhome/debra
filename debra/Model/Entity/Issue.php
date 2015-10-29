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

namespace Debra\Model\Entity;

use Debra\Model\AbstractModel;
use Silex\Application;

/**
 * issue model which represents an issue in jira & git repository
 *
 * Class Issue
 * @package Debra\Model
 */
class Issue extends AbstractModel
{
	/**
	 * @var mixed[]
	 */
	protected $data = array(
		'id'            => '',
		'key'           => '',
		'status'        => '',
		'summary'       => '',
		'last_updated'  => '',
		'url'           => '',
		'fixed_version' => '',
		'sprint'        => '',
		'branch'        => '',
	);

	/**
	 * @return array
	 */
	public function toArray()
	{
		$data = $this->data;
		foreach ($data as $key => $val) {

			// if DateTime convert to a string
			if ($val instanceof \DateTime) {
				$data[$key] = $val->format('m.d.Y H:i:s');
			}

			// if summary need to shorten it
			if ($key === 'summary') {
				if (strlen($val) > 40) {
					$data[$key] = substr($val, 0, 40) . '...';
				}
			}
		}

		return $data;
	}
}