<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marketeur extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        'username',
        'email',
        "phone"
    ];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function as_user(): BelongsTo
    {
        return $this->belongsTo(User::class, "as_user");
    }

    function chargOrders(): HasMany
    {
        return $this->hasMany(ChargeOrder::class, "owner");
    }
}
