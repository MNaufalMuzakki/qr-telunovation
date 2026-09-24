<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_code_id',
        'name',
        'email',
        'phone',
        'is_reward_claimed',
    ];

    protected function casts(): array
    {
        return [
            'is_reward_claimed' => 'boolean',
        ];
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'visits')
                    ->withPivot('scanned_at')
                    ->withTimestamps();
    }
}
