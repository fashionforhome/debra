<?php

use Monolog\Logger;

return array(

	/**
	 * user list with name as key and md5 hashed password as value
	 */
	'users' => array(
		'tester' => 'f5d1278e8109edd94e1e4197e04873b9'
	),

	/**
	 * setup for git repository
	 */
	'git' => array(
		'url'           => "https://[USER]:[HASH]@bitbucket.org/fashion4home/test_repo_tool_debra.git",
		'username'      => '',
		'password'      => '',
		'git_path'      => 'git',
		'git_repo_path' => __DIR__ . '/../storage/git'
	),

	/**
	 * jira restful api
	 */
	'jira' => array(
		'url' => 'https://[COMPANY].jira.com/',
		'username' => '',
		'password' => '',
	),

	/**
	 * logging setup
	 */
	'log' => array(
		'logfile'   => __DIR__ . '/../storage/logs/deletion.log',
		'level'     => Logger::INFO,
		'channel'   => 'user'
	)
);