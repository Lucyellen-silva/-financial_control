<?php

namespace Financeiro\Models;
use Illuminate\Database\Eloquent\Model;

class CategoryCost extends Model
{
	protected $fillable = [
		'name',
        'user_id',
        'deleted_at'
	];
}