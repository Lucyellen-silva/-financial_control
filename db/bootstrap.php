<?php

use Financeiro\Application;
use Financeiro\Plugins\DbPlugin;
use Financeiro\Plugins\AuthPlugin;
use Financeiro\ServiceContainer;

$serviceContainer = new ServiceContainer();
$app = new Application($serviceContainer);

$app->plugin(new DbPlugin());
$app->plugin(new AuthPlugin());

return $app;