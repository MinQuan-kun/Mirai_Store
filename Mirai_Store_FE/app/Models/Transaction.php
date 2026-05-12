<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',           // 'deposit', 'purchase', 'refund'
        'amount',
        'status',         // 'pending', 'completed', 'failed', 'cancelled'
        'description',
        'order_id',       // Order ID for purchases
        'reference_id',   // PayPal transaction ID, MoMo transaction ID, or Order ID
        'payment_method', // 'paypal', 'momo', 'wallet'
        'metadata',       // Additional data (exchange rate, currency, etc.)
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order associated with this transaction (for purchases)
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', '_id');
    }
}
