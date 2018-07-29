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

$app->get('/{name}', function (ServerRequestInterface $request) use ($app){
	$view = $app->service('view.render');
	return $view->render('test.html.twig', ['name' => $request->getAttribute('name')]);
});

$app->get('/home/{name}/{id}', function (ServerRequestInterface $request) {
	$response = new Response;
	$response->getBody()->write('Resonse');
	return $response;
});

$app->start();