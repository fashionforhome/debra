<?php /** @var \Silex\Application $app */

// service controller
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

// session helper
$app->register(new Silex\Provider\SessionServiceProvider());

// url generator
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// controller
$app->register(new Debra\ServiceProvider\ControllerServiceProvider());

// helpers
$app->register(new Debra\ServiceProvider\HelperServiceProvider());

// jira service
$app->register(new Debra\ServiceProvider\JiraServiceProvider());

// git service
$app->register(new Debra\ServiceProvider\GitServiceProvider());

// models & collections
$app->register(new Debra\ServiceProvider\ModelServiceProvider());

// twig template engine
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/../debra/Views',
));

// logging
$app->register(new Silex\Provider\MonologServiceProvider(), array(
	'monolog.logfile' => __DIR__.'/../storage/logs/development.log',
));

// extend monolog logging for multiple channel logging
$app->register(new Debra\ServiceProvider\MultipleMonologServiceProvider());
