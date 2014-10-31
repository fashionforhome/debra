<?php /** @var \Silex\Application $app */

$userLoggedIn = $app['controllers_factory'];
$userLoggedOut = $app['controllers_factory'];

// login and logout routes
$userLoggedOut->get('/', 'controller.main:getLoginForm')->bind('login.form');
$userLoggedOut->post('/login', 'controller.main:postLogin')->bind('login');
$userLoggedIn->get('/logout', 'controller.main:getLogout')->bind('logout');

// empty session cache
$userLoggedIn->get('/empty-session-cache', 'controller.main:getEmptySessionCache')->bind('empty-session-cache');

// overview
$userLoggedIn->get('/overview', 'controller.main:getOverview')->bind('overview');
$userLoggedIn->post('/select-branches', 'controller.main:postSaveSelection')->bind('select.branches');

// confirmation
$userLoggedIn->get('/confirmation', 'controller.main:getConfirmation')->bind('confirmation');
$userLoggedIn->post('/delete-branches', 'controller.main:deleteSelectedBranches')->bind('delete.branches');

// report
$userLoggedIn->get('/report', 'controller.main:getReport')->bind('report');

// need to be logged out
$userLoggedOut->before(function(\Symfony\Component\HttpFoundation\Request $request, \Silex\Application $app){

	if ($app['session']->get('user', null) !== null) {
		return $app->redirect(
			$app['url_generator']->generate('overview')
		);
	}

	return null;
});

// has to be logged in
$userLoggedIn->before(function(\Symfony\Component\HttpFoundation\Request $request, \Silex\Application $app){

	if ($app['session']->get('user', null) === null) {
		return $app->redirect(
			$app['url_generator']->generate('login.form')
		);
	}

	return null;
});

$app->mount('/', $userLoggedOut);
$app->mount('/user', $userLoggedIn);