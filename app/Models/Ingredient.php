<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Ingredient extends Model
{
    //
    protected $fillable = [
        'id', 'name', 'coverage', 'purchaseprice', 'created_by', 'updated_by'
    ];
}
