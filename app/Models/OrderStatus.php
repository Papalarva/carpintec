<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    public $incrementing = false;
    protected $keyType = 'int'; // smallint
    public $timestamps = false;

    protected $fillable = ['id', 'name'];
}