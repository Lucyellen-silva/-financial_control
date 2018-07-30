<?php

declare(strict_types=1);
namespace Financeiro;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Financeiro\Plugins\PluginInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\Response\SapiEmitter;

class Application 
{
	private $serviceContainer;

	public function __construct(serviceContainerInterface $serviceContainer)
	{
		$this->serviceContainer = $serviceContainer;
	}

	public function service($name)
	{
		return $this->serviceContainer->get($name);
	}

	public function addService(string $name, $service): void
	{
		is_callable($service) ? $this->serviceContainer->addLazy($name, $service) : $this->serviceContainer->add($name, $service);		
	}

	public function plugin(PluginInterface $plugin): void
	{
		$plugin->register($this->serviceContainer);
	}

	public function get($path, $action, $name = null): Application
	{
		$routing = $this->service('routing');
		$routing->get($name, $path, $action);
		return $this;
	}

	public function start()
	{
		$route 	  = $this->service('route');
		$request  = $this->service(RequestInterface::class);

		if(!$route){
			echo "page not found";
			exit;
		}

		foreach ($route->attributes as $key => $value) {
			$request = $request->withAttribute($key, $value);
		}

		$callable = $route->handler;
		$this->emitResponse($callable($request));
	}

	protected function emitResponse(ResponseInterface $response)
	{
		$emitter = new SapiEmitter;
		$emitter->emit($response);
	}
}