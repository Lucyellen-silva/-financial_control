<?php 

declare(strict_types=1);
namespace Financeiro\View;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;

class ViewRender implements ViewRenderInterface
{
	private $twigEnviroment;

	public function __construct(\twig_Environment $twigEnviroment)
	{
		$this->twigEnviroment = $twigEnviroment;
	}

	public function render(string $templete, array $context = []): ResponseInterface
	{
		$result = $this->twigEnviroment->render($templete, $context);
		$response = new Response;
		$response->getBody()->write($result);
		return $response;
	}
}