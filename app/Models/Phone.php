<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Phone extends Model
{
    //
    protected $fillable = [
        'id', 'personid', 'number', 'type', 'primary'
    ];
}
