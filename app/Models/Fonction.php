<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fonction extends Model
{
    use HasFactory;

    function electedConsulars(): BelongsToMany
    {
        return $this->belongsToMany(ElectedConsular::class, "elected_consulars_fonctions", "fonction_id", "elected_consular");
    }

    function company(): BelongsTo
    {
        return $this->belongsTo(ElectedConsularFonction::class, "company_id");
    }

    // function mandate(): BelongsTo
    // {
    //     return $this->belongsTo(ElectedConsularFonction::class, "mandate");
    // }
}
