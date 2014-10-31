<?php namespace Debra\Model\Parser;

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