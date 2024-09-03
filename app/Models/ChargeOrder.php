<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChargeOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        "transportor",
        "driver",
        "driver_permit_ref",
        "camion_number",
        "product_volume",
        "driver_phone",
        "owner",
        "logistique"
    ];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function logistique(): BelongsTo
    {
        return $this->belongsTo(Logistique::class, "logistique");
    }
}
