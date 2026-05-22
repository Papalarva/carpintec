<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasUuids;

    protected $table = 'order_status_history';
    
    // Apagamos los timestamps de Laravel
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'status_id',
        'comment',
        'user_id',
        'changed_at',
    ];

    protected $casts = [
        'status_id'  => \App\Enums\OrderStatus::class, // Convierte el número al Enum
        'changed_at' => 'datetime',                    // Convierte la fecha a Carbon
    ];

    // Relaciones
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}