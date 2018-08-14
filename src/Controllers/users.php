<?php

use Psr\Http\Message\ServerRequestInterface;

$app
	->get('/users', function() use($app){
        $auth  = $app->service('auth');
        if($auth->user()->getId() !== 1){
            return $app->route('category-costs.list');
        }

		$view 		= $app->service('view.render');
		$repository = $app->service('users.repository');
		$users = $repository->all();
		return $view->render('users/list.html.twig', [
			'users' => $users
		]);
	}, 'users.list')

	->get('/users/new', function() use($app){
        $auth  = $app->service('auth');
        if($auth->user()->getId() !== 1){
            return $app->route('category-costs.list');
        }

		$view = $app->service('view.render');
		return $view->render('users/create.html.twig');
	}, 'users.new')

	->post('/users/store', function(ServerRequestInterface $request) use ($app){
        $auth  = $app->service('auth');

        if($auth->user()->getId() !== 1){
            return $app->route('category-costs.list');
        }

		$data 		      = $request->getParsedBody();
        $data['password'] = $auth->hashPassword($data['password']);
		$repository       = $app->service('users.repository');

		$repository->create($data);

		return $app->route('users.list');
	}, 'users.store')

	->get('/users/{id}/edit', function(ServerRequestInterface $request) use($app){
        $auth  = $app->service('auth');
        if($auth->user()->getId() !== 1){
            return $app->route('category-costs.list');
        }

		$view 	  	= $app->service('view.render');
		$repository = $app->service('users.repository');
		$id   	  	= $request->getAttribute('id');
		$user 		= $repository->find($id);
		return $view->render('users/edit.html.twig', [
			'user' => $user
		]);
	}, 'users.edit')

	->post('/users/{id}/update', function(ServerRequestInterface $request) use($app){
        $auth  = $app->service('auth');
        if($auth->user()->getId() !== 1){
            return $app->route('category-costs.list');
        }

	    $repository = $app->service('users.repository');
		$id   	  	= $request->getAttribute('id');
		$user 		      = $repository->find($id);
		$data 	  	      = $request->getParsedBody();
        $data['password'] = $auth->hashPassword($data['password']);
		$repository->update($id, $data);
		return $app->route('users.list');
	}, 'users.update')

	->get('/users/{id}/show', function(ServerRequestInterface $request) use($app){
        $auth  = $app->service('auth');
        if($auth->user()->getId() !== 1){
            return $app->route('category-costs.list');
        }
	    $view 	  	= $app->service('view.render');
		$repository = $app->service('users.repository');
		$id   	  	= $request->getAttribute('id');
		$user 		= $repository->find($id);
		return $view->render('users/show.html.twig', [
			'user' => $user
		]);
	}, 'users.show')

	->get('/users/{id}/delete', function(ServerRequestInterface $request) use($app){
        $auth  = $app->service('auth');
        if($auth->user()->getId() !== 1){
            return $app->route('category-costs.list');
        }

        $repository = $app->service('users.repository');
		$id   	  	= $request->getAttribute('id');
		$user 		= $repository->delete($id);
		return $app->route('users.list');
	}, 'users.delete');