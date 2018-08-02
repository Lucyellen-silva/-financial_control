<?php

declare(strict_types=1);

namespace Financeiro\Plugins;

use Financeiro\ServiceContainerInterface;
use Interop\Container\ContainerInterface;
use Financeiro\Auth\Auth;
use Financeiro\Auth\JasnyAuth;

class AuthPlugin implements PluginInterface
{
    public function register(ServiceContainerInterface $container)
    {
        $container->addLazy(
            'jasny.auth', function (ContainerInterface $container) {
            return new JasnyAuth($container->get('users.repository'));
        });

        $container->addLazy(
            'auth', function (ContainerInterface $container) {
            return new Auth($container->get('jasny.auth'));
        });
    }
}