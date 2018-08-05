<?php
/**
 * Created by PhpStorm.
 * User: lucy
 * Date: 04/08/18
 * Time: 15:21
 */

namespace Financeiro\Models;
use Illuminate\Database\Eloquent\Model;

class BillPay extends Model
{
    protected $fillable = [
        'date_launch',
        'name',
        'value',
        'user_id',
        'category_cost_id'
    ];

    public function categoryCost()
    {
        return $this->belongsTo(CategoryCost::class);
    }
}