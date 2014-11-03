<?php namespace Debra\Model\Adapter;

use Debra\Model\Parser\JiraParser;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;

/**
 * adapter for fetching issue data from an jira endpoint
 *
 * Class JiraAdapter
 * @package Debra\Model\Adapter
 */
class JiraAdapter
{
	/**
	 * @var \Guzzle\Http\Client
	 */
	private $client;

	/**
	 * @var JiraParser
	 */
	private $parser;

	/**
	 * @var string
	 */
	private $baseUrl;

	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var string
	 */
	private $password;

	/**
	 * list of attribute ids which will be fetched from the endpoint
	 *
	 * @var string[]
	 */
	private static $attributeList = array('id', 'key', 'updated', 'fixVersions', 'summary', 'customfield_10560', 'status');

	/**
	 * @var int
	 */
	const API_VERSION = 2;

	/**     *
	 * @param \Guzzle\Http\Client $client
	 * @param JiraParser $parser
	 * @param string $baseUrl
	 * @param string $username
	 * @param string $password
	 */
	public function __construct(Client $client, JiraParser $parser, $baseUrl, $username, $password)
	{
		// init the client
		$this->client = new Client();
		$this->client->setDefaultOption('auth', array($username, $password));
		$this->client->setDefaultOption('verify', false);

		$this->parser = $parser;
		$this->baseUrl = $baseUrl;
		$this->username = $username;
		$this->password = $password;
	}

	/**
	 * builds an api endpoint url
	 *
	 * @param string $uri
	 * @return string
	 */
	private function buildUrl($uri)
	{
		return $this->baseUrl . "rest/api/" . static::API_VERSION . "/" . $uri;
	}

	/**
	 * returns a collection of issue by key
	 *
	 * @param array $keyList
	 * @return array[]
	 */
	public function getIssuesByKeys($keyList = array())
	{
		// if no keys given
		if (empty($keyList)) {
			return array();
		}

		// build request for given issue keys
		$req = $this->client->post($this->buildUrl("search"), array(), json_encode(array(
			'jql'           => 'key IN ('.implode(",", $keyList).')',
			'startAt'       => 0,
			'maxResults'    => count($keyList),
			'fields'        => static::$attributeList
		)));
		$req->setHeader('Content-Type', 'application/json');
		// request the jira issue data
		try {
			$res = $req->send($req);
			$resJson = $res->json();
		} catch (ClientErrorResponseException $e) {

			// if there are errors most likely the issue do not exists, so remove them from search list
			$errors = $e->getResponse()->json();
			foreach ($errors['errorMessages'] as $error) {
				if (
					preg_match("/An issue with key '([^']+)' does not exist for field 'key'\./mi", $error, $match) ||
					preg_match("/The issue key '([^']+)' for field 'key' is invalid\./mi", $error, $match)
				) {
					unset($keyList[ array_search($match[1], $keyList) ]);
				}
			}

			// request again
			return $this->getIssuesByKeys($keyList);
		}

		// loop through all results and parse them into a clean data set
		$issues = array();
		foreach ($resJson['issues'] as $issueData) {
			$cleanData = $this->parser->parseJiraIssue($issueData);
			$issues[$cleanData['key']] = $cleanData;
		}

		return $issues;
	}
}