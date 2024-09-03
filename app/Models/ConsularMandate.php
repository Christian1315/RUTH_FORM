<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConsularMandate extends Model
{
    use HasFactory;

    protected $table = "consulars_mandates";

    protected $fillable = [
        "elected_consular",
        "mandate_id",
        "poste_id"
    ];

    function electedConsular(): BelongsTo
    {
        return $this->belongsTo(ElectedConsular::class, "elected_consular");
    }

    function mandates(): HasMany
    {
        return $this->hasMany(Mandate::class, "mandate_id");
    }

    function poste(): HasOne
    {
        return $this->hasOne(Poste::class, "poste_id");
    }
}
