<?php

use Psr\Http\Message\ServerRequestInterface;
use Financeiro\Models\CategoryCost;
use Financeiro\ServiceContainer;
use Financeiro\Application;
use Financeiro\Plugins\RoutePlugin;
use Financeiro\Plugins\ViewPlugin;
use Financeiro\Plugins\DbPlugin;
use Financeiro\Plugins\AuthPlugin;
use Zend\Diactoros\Response;

require_once __DIR__ . '/../vendor/autoload.php';

$serviceContainer = new ServiceContainer();
$app = new Application($serviceContainer);

$app->plugin(new RoutePlugin());
$app->plugin(new ViewPlugin());
$app->plugin(new DbPlugin());
$app->plugin(new AuthPlugin());

$app->get('/home/{name}/{id}', function (ServerRequestInterface $request) {
	$response = new Response;
	$response->getBody()->write('Resonse');
	return $response;
});

require_once __DIR__.'/../src/Controllers/category-costs.php';
require_once __DIR__.'/../src/Controllers/users.php';
require_once __DIR__.'/../src/Controllers/auth.php';

$app->start();