<?php 

declare(strict_types=1);
namespace Financeiro\View;
use Psr\Http\Message\ResponseInterface;

interface ViewRenderInterface
{
	public function render(string $templete, array $context = []): ResponseInterface;
}