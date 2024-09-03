<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chargement extends Model
{
    use HasFactory;
    protected $fillable = [
        "product",
        "qty",
        "immatriculation",
        "driver_identification",
        "destination",
        "emetteur",
        "owner"
    ];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product");
    }
}
