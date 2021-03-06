<?php

declare(strict_types=1);
namespace Financeiro;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Financeiro\Plugins\PluginInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\Response\SapiEmitter;

class Application 
{
	private $serviceContainer;
	private $befores = [];

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

	public function post($path, $action, $name = null): Application
	{
		$routing = $this->service('routing');
		$routing->post($name, $path, $action);
		return $this;
	}

	public function redirect($path): ResponseInterface
	{
		return new RedirectResponse($path);
	}

	public function route(string $name, array $params = [])
	{
		$generator = $this->service('routing.generator');
		$path	   = $generator->generate($name, $params);
		return $this->redirect($path);
	}

	public function before(callable $callback)
    {
        array_push($this->befores, $callback);
        return $this;
    }

    protected function runBefores(): ?ResponseInterface
    {
        foreach ($this->befores as $callaback){
            $result = $callaback($this->service(RequestInterface::class));
            if($result instanceof ResponseInterface){
                return $result;
            }
        }

        return null;
    }

	public function start(): void
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

		$result = $this->runBefores();

        if ($result){
            $this->emitResponse($result);
            return;
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