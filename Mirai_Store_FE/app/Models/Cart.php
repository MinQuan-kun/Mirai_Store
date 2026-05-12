<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\\Laravel\\Eloquent\\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'quantity',
        'price_at_time', // Store price at time of adding to cart
    ];

    protected $casts = [
        'price_at_time' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * Get the user that owns the cart item
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the game in the cart
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Get subtotal for this cart item
     */
    public function getSubtotalAttribute()
    {
        return $this->price_at_time * $this->quantity;
    }
}
