<?php


use Psr\Http\Message\ServerRequestInterface;

$app
    ->get('/home', function() use($app){
        $view                 = $app->service('view.render');
        $repositoryCategory   = $app->service('category-costs.repository');
        $repositorystatement  = $app->service('statement.repository');
        $auth                 = $app->service('auth');
        $userId               = $auth->user()->getId();

        $year        = date("Y");
        $categories  = [];
        $statement   = [];
        $meses       = [];

        for ($i = 1; $i <= date('m'); $i++) {

            $ultimo_dia = date("t", mktime(0,0,0,$i ,'01',$year));

            if ($i <= '9'){
                $i = '0'.$i;
            }

            $dateStart = $year . '-' . $i . '-' . '01' ;
            $dateEnd   = $year . '-' . $i . '-' . $ultimo_dia ;
            $category  = $repositoryCategory->sumByPeriod($dateStart, $dateEnd, $userId);

            if(!empty($category)){
                $meses[]           = $i;
                $categories[$i]      = $category;
                $statement[$i]     = $repositorystatement->all($dateStart, $dateEnd, $userId);
            }
        }

        $dates = [
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        ];

        return $view->render('home/home.html.twig', [
            'categories' => $categories,
            'statements' => $statement,
            'meses'      => $meses,
            'dates'      => $dates
        ]);
    }, 'home');