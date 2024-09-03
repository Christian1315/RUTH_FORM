<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsularPoste extends Model
{
    use HasFactory;

    protected $table = "consulars_postes";

    protected $fillable = [
        "elected_consular",
        "poste_id",
        "mandate_id"
    ];

    function poste(): BelongsTo
    {
        return $this->belongsTo(Poste::class, "poste_id");
    }

    function consular(): BelongsTo
    {
        return $this->belongsTo(ElectedConsular::class, "elected_consular");
    }

    function mandate(): BelongsTo
    {
        return $this->belongsTo(Mandate::class, "mandate_id");
    }
}
