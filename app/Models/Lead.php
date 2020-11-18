<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Lead extends Model
{
    //
    protected $fillable = [
        'id', 'personid', 'active', 'created_by', 'updated_by'
    ];
}
