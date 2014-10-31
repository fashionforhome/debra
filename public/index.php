<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;

// include the routes definition
include __DIR__ . '/../configs/routes.php';

// register services
include __DIR__ . '/../configs/services.php';

$app->run();