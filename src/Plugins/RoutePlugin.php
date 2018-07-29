<?php

declare(strict_types=1);
namespace Financeiro\Plugins;
use Aura\Router\RouterContainer;
use Financeiro\ServiceContainerInterface;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\ServerRequestFactory;
use Interop\Container\ContainerInterface;

class RoutePlugin implements PluginInterface
{
	public function register(ServiceContainerInterface $container)
	{
		$routerContainer = new RouterContainer();
		/* Registrar as rotas da aplicação */
		$map 			 = $routerContainer->getMap();
		/* identificar a rota que esta sendo acessada */
		$matcher 		 = $routerContainer->getMatcher();

		/* Gerar links nas rotas registradas */
		$generator 		 = $routerContainer->getGenerator();
		$request 		 = $this->getRequest();
		$container->add('routing', $map);
		$container->add('routing.matcher', $matcher);
		$container->add('routing.generator', $generator);
		$container->add(RequestInterface::class, $this->getRequest());
		
		$container->addLazy('route', function(ContainerInterface $container) {
			$matcher = $container->get('routing.matcher');
			$request = $container->get(RequestInterface::class);
			return $matcher->match($request);
		});
	}

	protected function getRequest(): RequestInterface
    {
        return ServerRequestFactory::fromGlobals(
            $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
        );
    }
}