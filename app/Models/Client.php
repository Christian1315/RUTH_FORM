<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "type",
        "city",
        "company",
        "rccm",
        "number",
        "phone",
        "email",
        "comments",
        "country_prefix",
        "sexe",
        "is_proprietor",
        "is_locator",
        "is_render",
        "is_avaliseur",
        "visible",
        "delete_at"
    ];

    function Type(): BelongsTo
    {
        return $this->belongsTo(ClientType::class, "type");
    }

    function City(): BelongsTo
    {
        return $this->belongsTo(City::class, "city");
    }
}
