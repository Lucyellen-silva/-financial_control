<?php

use Psr\Http\Message\ServerRequestInterface;
use Financeiro\ServiceContainer;
use Psr\Http\Message\RequestInterface;
use Financeiro\Application;
use Financeiro\Plugins\RoutePlugin;
use Financeiro\Plugins\ViewPlugin;
use Zend\Diactoros\Response;

require_once __DIR__ . '/../vendor/autoload.php';

$serviceContainer = new ServiceContainer();
$app = new Application($serviceContainer);

$app->plugin(new RoutePlugin());
$app->plugin(new ViewPlugin());

$app->get('/home/{name}/{id}', function (ServerRequestInterface $request) {
	$response = new Response;
	$response->getBody()->write('Resonse');
	return $response;
});

$app->get('/category-costs', function() use($app){
	$view = $app->service('view.render');
	return $view->render('category-costs/list.html.twig');
});

$app->start();