<?php


use Psr\Http\Message\ServerRequestInterface;

$app
    ->get('/home', function() use($app){
        $view                = $app->service('view.render');
//        $repository          =  $app->service('home.repository');
////        $repositoryCategory  =  $app->service('category-costs.repository');
////        $repositorystatement =  $app->service('statement.repository');
//        $auth                = $app->service('auth');
//        $userId              = $auth->user()->getId();
//
//        $year      = date("Y");
//        $category  = [];
//        $statement = [];
//        for ($i = 1; $i <= data('m'); $i++) {
//            $ultimo_dia  = date("t", mktime(0,0,0,$i ,'01',$year));
//            $dateStart   = $year . '-' . $i . '-' . '01-' ;
//            $dateEnd     = $year . '-' . $i . '-' . $ultimo_dia ;
//            $category    = $repositoryCategory->sumByPeriod($dateStart, $dateEnd, $userId);
//            $statement   = $repositorystatement->all($dateStart, $dateEnd, $userId);
//        }
//
//        var_dump($category );
//        echo '-------------------';
//        echo '<br>';
//        echo $statement;
//        echo '-------------------';
//        echo '<br>';
//        exit;

        return $view->render('home/home.html.twig');
    });