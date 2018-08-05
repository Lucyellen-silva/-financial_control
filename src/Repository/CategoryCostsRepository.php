<?php
/**
 * Created by PhpStorm.
 * User: lucy
 * Date: 04/08/18
 * Time: 19:30
 */

namespace Financeiro\Repository;

use Financeiro\Models\CategoryCost;

class CategoryCostsRepository extends DefaultRepository
{
    public function __construct()
    {
        parent::__construct(CategoryCost::class);
    }

    public function sumByPeriod(string $dateStart, string $dateEnd, int $userId): array
    {
        $categories = CategoryCost::query()
            ->selectRaw('category_costs.name, sum(value) as value')
            ->leftJoin('bill_pays', 'bill_pays.category_cost_id', '=', 'category_costs.id')
            ->whereBetween('date_launch', [$dateStart, $dateEnd])
            ->where('category_costs.user_id', $userId)
            ->whereNotNull('bill_pays.category_cost_id')
            ->groupBy('category_costs.name')
            ->get();

        return $categories->toArray();
    }
}