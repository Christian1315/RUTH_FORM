<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStock extends Model
{
    use HasFactory;

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, "manager");
    }

    function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product")->with(["category", "type", "inventory"]);
    }
}
