<?php
/**
 * Created by PhpStorm.
 * User: lucy
 * Date: 04/08/18
 * Time: 19:28
 */

namespace Financeiro\Repository;


use Illuminate\Contracts\Cache\Repository;

interface CategoryCostsRepositoryInterface extends Repository
{
    public function sumByPeriod(string $dateStart, string $dateEnd, int $userId): array;
}