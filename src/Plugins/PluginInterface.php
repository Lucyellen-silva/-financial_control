<?php

namespace Financeiro\Plugins;
use Financeiro\ServiceContainerInterface;

interface PluginInterface
{
	public function register(ServiceContainerInterface $container);
}