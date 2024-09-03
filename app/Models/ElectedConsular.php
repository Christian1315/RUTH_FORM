<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElectedConsular extends Model
{
    use HasFactory;

    protected $fillable = [
        "owner",
        "ifu",
        "npi",
        "firstname",
        "lastname",
        "sexe",
        "photo",
        // "validated_date",
        "birth_date",
        "place_of_birth",
        "country_of_birth",
        "nationnality",
        "phone",
        "email",
    ];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function company_fonction_mandate(): HasMany
    {
        return $this->hasMany(CompanyConsular::class, "elected_consular")->with(["company", "fonction", "mandate"]);
    }

    function postes(): HasMany
    {
        return $this->hasMany(ConsularPoste::class, "elected_consular")->with(["mandate", "consular"]);
    }
}
