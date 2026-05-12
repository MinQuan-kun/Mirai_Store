<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'avatar',
        'balance',
    ];
    protected $attributes = [
        'role' => 'user',
        'status' => 'active',
        'avatar' => null,
        'balance' => 0,
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user has sufficient balance
     */
    public function hasBalance($amount): bool
    {
        return (float) $this->balance >= (float) $amount;
    }

    public function addBalance($amount): void
    {
        // Cast to float to ensure numeric operation
        $currentBalance = (float) ($this->balance ?? 0);
        $newBalance = $currentBalance + (float) $amount;

        // Update directly instead of using increment to avoid MongoDB string issues
        $this->update(['balance' => $newBalance]);
        $this->refresh();
    }

    public function deductBalance($amount): bool
    {
        if (!$this->hasBalance($amount)) {
            return false;
        }

        // Cast to float to ensure numeric operation
        $currentBalance = (float) ($this->balance ?? 0);
        $newBalance = $currentBalance - (float) $amount;

        // Update directly instead of using decrement
        $this->update(['balance' => $newBalance]);
        $this->refresh();

        return true;
    }

    /**
     * Check if user owns a specific game
     */
    public function ownsGame($gameId): bool
    {
        return \App\Models\OrderItem::whereHas('order', function ($query) {
            $query->where('user_id', $this->_id)
                ->where('status', 'completed');
        })->where('game_id', $gameId)->exists();
    }
}
