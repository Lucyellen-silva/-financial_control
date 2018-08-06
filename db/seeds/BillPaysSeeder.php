<?php

use Phinx\Seed\AbstractSeed;

class BillPaysSeeder extends AbstractSeed
{
    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $categories;

    public function run()
    {
        require __DIR__.'/../bootstrap.php';

        $faker            = \Faker\Factory::create('pt_BR');
        $billPays         = $this->table('bill_pays');
        $this->categories = \Financeiro\Models\CategoryCost::all();
        $faker->addProvider($this);

        $data = [];
        foreach (range(1, 20) as $value) {
            $userId = rand(1,4);
            $plots  = rand(1,20);
            $data[] = [
                'date_launch'       => $faker->dateTimeBetween('-1 month')->format('Y-m-d'),
                'name'              => $faker->word,
                'value'             => $faker->randomFloat(2,10,1000),
                'user_id'           => $userId,
                'category_cost_id'  => $faker->categoryId($userId),
                'plots'             => $plots,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s')
            ];
        }
        $billPays->insert($data)->save();
    }

    public function categoryId($userId)
    {
        $categories = $this->categories->where('user_id', $userId);
        $categories = $categories->pluck('id');
        return \Faker\Provider\Base::randomElement($categories->toArray());
    }
}