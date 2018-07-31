<?php

declare(strict_types=1);
namespace Financeiro\Plugins;
use Aura\Router\RouterContainer;
use Financeiro\ServiceContainerInterface;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\ServerRequestFactory;
use Interop\Container\ContainerInterface;
use Financeiro\View\ViewRender;

class ViewPlugin implements PluginInterface
{
	public function register(ServiceContainerInterface $container)
	{
		$container->addLazy('twig', function(ContainerInterface $container) {
			$loader = new \Twig_Loader_Filesystem(__DIR__.'/../../templetes');
			$twig 	= new \Twig_Environment($loader);

			$generator = $container->get('routing.generator');
			
			$twig->addFunction(new \Twig_SimpleFunction('route', 
				function(string $name, array $params = []) use ($generator){
					return $generator->generate($name, $params);
			}));
			return $twig;
		});

		$container->addLazy('view.render', function(ContainerInterface $container){
			$TwigEnvironment = $container->get('twig');
			return new ViewRender($TwigEnvironment);
		});
	}
}