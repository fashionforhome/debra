<?php namespace Debra\Model\Entity;

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