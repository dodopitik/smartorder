<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'order_code',
        'user_id',
        'subtotal',
        'tax',
        'status',
        'payment_method',
        'table_number',
        'notes',
        'grandtotal',
        'is_disbursed',
        'disbursed_at',
    ];

    protected $dates = ['deleted_at'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'order_items')
            ->withPivot(['quantity', 'price', 'tax', 'total_price'])
            ->withTimestamps();
    }
}
