<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplyProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'comments',
        'product',
        'manager',
        'quantity',
    ];

    function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner');
    }

    function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product');
    }
}
