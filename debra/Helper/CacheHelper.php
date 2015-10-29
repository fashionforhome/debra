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

namespace Debra\Helper;

use Silex\Application;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * cache layer for branch and jira issue data
 *
 * Class CacheHelper
 * @package Debra\Helper
 */
class CacheHelper
{
	/**
	 * @var \Symfony\Component\HttpFoundation\Session\Session
	 */
	private $session;

	/**
	 * @param Session $session
	 */
	public function __construct(Session $session)
	{
		$this->session = $session;
	}

	/**
	 * @param string $key
	 * @param mixed $val
	 * @return $this
	 */
	public function set($key, $val)
	{
		$this->session->set($key, $val);

		return $this;
	}

	/**
	 * @param string $key
	 * @param mixed|null $default
	 * @return mixed|null
	 */
	public function get($key, $default = null)
	{
		if ($this->session->has($key)) {
			return $this->session->get($key);
		}

		return $default;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function has($key)
	{
		return $this->session->has($key);
	}

	/**
	 * @param string $key
	 * @return $this
	 */
	public function remove($key)
	{
		$this->session->remove($key);

		return $this;
	}
}