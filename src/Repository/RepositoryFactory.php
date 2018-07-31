<?php 

namespace Financeiro\Repository;
use Financeiro\Repository\DefaultRepository;

class RepositoryFactory
{
	public static function factory(string $modelClass)
	{
		return new DefaultRepository($modelClass);
	}
} 