<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'game_id',
        'price',
        'quantity',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * Get the order that owns the order item
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the game in the order
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get subtotal for this order item
     */
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
