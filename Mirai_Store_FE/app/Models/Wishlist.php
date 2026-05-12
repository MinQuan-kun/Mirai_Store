<?php

namespace App\Models;

use MongoDB\\Laravel\\Eloquent\\Model;

class Wishlist extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'game_id',
    ];

    /**
     * Get the user that owns the wishlist item
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the game that is wishlisted
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }
}
