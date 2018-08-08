<?php

use Psr\Http\Message\ServerRequestInterface;

$app
    ->get('/bill-pays', function () use ($app) {
        $view       = $app->service('view.render');
        $repository = $app->service('bill-pay.repository');
        $auth       = $app->service('auth');
        $bills      = $repository->findByField('user_id', $auth->user()->getId());

        return $view->render('bill-pays/list.html.twig', [
                'bills' => $bills
        ]);

    }, 'bill-pays.list')

    ->get('/bill-pays/new', function () use ($app) {
        $view               = $app->service('view.render');
        $auth               = $app->service('auth');
        $categoryRepository = $app->service('category-costs.repository');
        $categories         = $categoryRepository->findByField('user_id', $auth->user()->getId());
        $mesCobranca        = [
            '1' => 'Mês Atual',
            '2' => 'Mês que vem',
        ];

        return $view->render('bill-pays/create.html.twig', [
            'categories' => $categories,
            'mesCobranca'=> $mesCobranca
        ]);

    }, 'bill-pays.new')

    ->post('/bill-pays/store', function (ServerRequestInterface $request) use ($app) {
        $data                     = $request->getParsedBody();
        $repository               = $app->service('bill-pay.repository');
        $categoryRepository       = $app->service('category-costs.repository');
        $auth                     = $app->service('auth');
        $data['user_id']          = $auth->user()->getId();
        $data['date_launch']      = dateParse($data['date_launch']);
        $data['value']            = numberParse($data['value']);
        $data['category_cost_id'] = $categoryRepository->findOneBy([
                'id'      => $data['category_cost_id'],
                'user_id' => $auth->user()->getId()
        ])->id;

        if(empty($data['plots'])){
            $repository->create($data);
        } else {
            $repository->createPlots($data);
        }

        return $app->route('bill-pays.list');

    }, 'bill-pays.store')

    ->get('/bill-pays/{id}/edit', function (ServerRequestInterface $request) use ($app) {
        $view       = $app->service('view.render');
        $repository = $app->service('bill-pay.repository');
        $id         = $request->getAttribute('id');
        $auth       = $app->service('auth');
        $bill       = $repository->findOneBy([
                'id'      => $id,
                'user_id' => $auth->user()->getId()
        ]);

        $categoryRepository = $app->service('category-costs.repository');
        $categories         = $categoryRepository->findByField('user_id', $auth->user()->getId());
        $mesCobranca        = [
            '1' => 'Mês Atual',
            '2' => 'Mês que vem',
        ];

        return $view->render('bill-pays/edit.html.twig', [
            'bill'       => $bill,
            'categories' => $categories,
            'mesCobranca'=> $mesCobranca
        ]);
    }, 'bill-pays.edit')

    ->post('/bill-pays/{id}/update', function (ServerRequestInterface $request) use ($app) {
        $repository               = $app->service('bill-pay.repository');
        $categoryRepository       = $app->service('category-costs.repository');
        $id                       = $request->getAttribute('id');
        $data                     = $request->getParsedBody();
        $auth                     = $app->service('auth');
        $data['user_id']          = $auth->user()->getId();
        $data['date_launch']      = dateParse($data['date_launch']);
        $data['value']            = numberParse($data['value']);
        $data['category_cost_id'] = $categoryRepository->findOneBy([
                'id'      => $data['category_cost_id'],
                'user_id' => $auth->user()->getId()
        ])->id;

        $repository->updatePlots([
                'id'      => $id,
                'user_id' => $auth->user()->getId()
        ], $data);

        return $app->route('bill-pays.list');

    }, 'bill-pays.update')

    ->get('/bill-pays/{id}/show', function (ServerRequestInterface $request) use ($app) {
        $view       = $app->service('view.render');
        $repository = $app->service('bill-pay.repository');
        $id         = $request->getAttribute('id');
        $auth       = $app->service('auth');
        $bill       = $repository->findOneBy([
                'id'      => $id,
                'user_id' => $auth->user()->getId()
        ]);

        return $view->render('bill-pays/show.html.twig', [
                'bill' => $bill
        ]);

    }, 'bill-pays.show')

    ->get('/bill-pays/{id}/delete', function (ServerRequestInterface $request) use ($app) {
        $repository = $app->service('bill-pay.repository');
        $id         = $request->getAttribute('id');
        $auth       = $app->service('auth');

        $repository->deletePlots([
                'id'      => $id,
                'user_id' => $auth->user()->getId()
        ]);

        return $app->route('bill-pays.list');

    }, 'bill-pays.delete');
