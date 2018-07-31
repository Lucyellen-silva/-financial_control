<?php

declare(strict_types=1);
namespace Financeiro\Plugins;

use Financeiro\ServiceContainerInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use Financeiro\Repository\RepositoryFactory;
use Interop\Container\ContainerInterface;
use Financeiro\Models\CategoryCost;
use Financeiro\Models\User;

class Dbplugin implements PluginInterface
{
	public function register(ServiceContainerInterface $container)
	{
		$capsule = new Capsule;
		$config  = include __DIR__.'/../../config/db.php';
		$capsule->addConnection($config['development']);
		$capsule->bootEloquent();
		$container->add('repository.factory', new RepositoryFactory);
		$container->addLazy('category-costs.repository', function (ContainerInterface $container){
			return $container->get('repository.factory')->factory(CategoryCost::class);
		});

		$container->addLazy('users.repository', function (ContainerInterface $container){
			return $container->get('repository.factory')->factory(User::class);
		});
	}
}