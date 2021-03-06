<?php

declare(strict_types=1);
namespace Financeiro\Plugins;

use Financeiro\Models\BillReceive;
use Financeiro\Repository\CategoryCostsRepository;
use Financeiro\Repository\HomeRepository;
use Financeiro\Repository\StatementsRepository;
use Financeiro\ServiceContainerInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use Financeiro\Repository\RepositoryFactory;
use Interop\Container\ContainerInterface;
use Financeiro\Models\User;
use Financeiro\Models\BillPay;

class Dbplugin implements PluginInterface
{
	public function register(ServiceContainerInterface $container)
	{
		$capsule = new Capsule;
		$config  = include __DIR__.'/../../config/db.php';
		$capsule->addConnection($config['default_connection']);
		$capsule->bootEloquent();
		$container->add('repository.factory', new RepositoryFactory);

		$container->addLazy('category-costs.repository', function (){
			return new CategoryCostsRepository;
		});

        $container->addLazy('bill-receive.repository', function (ContainerInterface $container){
            return $container->get('repository.factory')->factory(BillReceive::class);
        });

        $container->addLazy('bill-pay.repository', function (ContainerInterface $container) {
            return $container->get('repository.factory')->factory(BillPay::class);
        });

        $container->addLazy('users.repository', function (ContainerInterface $container){
			return $container->get('repository.factory')->factory(User::class);
		});

        $container->addLazy('statement.repository', function () {
            return new StatementsRepository;
        });
	}
}