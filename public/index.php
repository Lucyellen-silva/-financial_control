<?php

use Psr\Http\Message\ServerRequestInterface;
use Financeiro\Models\CategoryCost;
use Financeiro\ServiceContainer;
use Psr\Http\Message\RequestInterface;
use Financeiro\Application;
use Financeiro\Plugins\RoutePlugin;
use Financeiro\Plugins\ViewPlugin;
use Financeiro\Plugins\DbPlugin;
use Zend\Diactoros\Response;

require_once __DIR__ . '/../vendor/autoload.php';

$serviceContainer = new ServiceContainer();
$app = new Application($serviceContainer);

$app->plugin(new RoutePlugin());
$app->plugin(new ViewPlugin());
$app->plugin(new DbPlugin());

$app->get('/home/{name}/{id}', function (ServerRequestInterface $request) {
	$response = new Response;
	$response->getBody()->write('Resonse');
	return $response;
});

$app
	->get('/category-costs', function() use($app){
		$view = $app->service('view.render');
		$categories = new CategoryCost;
		$categories = $categories->all();
		return $view->render('category-costs/list.html.twig', [
			'categories' => $categories
		]);
	}, 'category-costs.list')

	->get('/category-costs/new', function() use($app){
		$view = $app->service('view.render');
		return $view->render('category-costs/create.html.twig');
	}, 'category-costs.new')

	->post('/category-costs/store', function(ServerRequestInterface $request) use ($app){
		$data 		= $request->getParsedBody();
		\Financeiro\Models\CategoryCost::create($data);
		return $app->route('category-costs.list');
	}, 'category-costs.store')

	->get('/category-costs/{id}/edit', function(ServerRequestInterface $request) use($app){
		$view 	  = $app->service('view.render');
		$id   	  = $request->getAttribute('id');
		$category = \Financeiro\Models\CategoryCost::find($id);
		return $view->render('category-costs/edit.html.twig', [
			'category' => $category
		]);
	}, 'category-costs.edit')

	->post('/category-costs/{id}/update', function(ServerRequestInterface $request) use($app){
		$id   	  = $request->getAttribute('id');
		$category = \Financeiro\Models\CategoryCost::findOrFail($id);
		$data 	  = $request->getParsedBody();
		$category->fill($data);
		$category->save();
		return $app->route('category-costs.list');
	}, 'category-costs.update');;

$app->start();