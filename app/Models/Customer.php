<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $phone
 * @property Carbon|null $birth_date
 * @property bool $accepts_marketing
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @property-read Collection<int, Address> $addresses
 * @property-read Cart|null $cart
 * @property-read Collection<int, Quotation> $quotations
 * @property-read Collection<int, Order> $orders
 */
class Customer extends Model
{
    use HasFactory, HasUuids;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'accepts_marketing' => 'boolean',
        ];
    }

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación N:1 — Cuenta de usuario vinculada a este cliente.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación 1:N — Direcciones registradas por este cliente.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Relación 1:1 — Carrito de compras activo del cliente.
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Relación 1:N — Cotizaciones solicitadas por este cliente.
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    /**
     * Relación 1:N — Pedidos realizados por este cliente.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
