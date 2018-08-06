<?php

namespace Financeiro\Models;

use Illuminate\Database\Eloquent\Model;

class BillReceive extends Model
{
    protected $fillable = [
        'date_launch',
        'name',
        'value',
        'user_id',
        'deleted_at'
    ];
}