<?php

declare(strict_types=1);
namespace Financeiro\Plugins;

use Financeiro\ServiceContainerInterface;
use Illuminate\Database\Capsule\Manager as Capsule;

class Dbplugin implements PluginInterface
{
	public function register(ServiceContainerInterface $container)
	{
		$capsule = new Capsule;
		$config  = include __DIR__.'/../../config/db.php';
		$capsule->addConnection($config['development']);
		$capsule->bootEloquent();
	}
}