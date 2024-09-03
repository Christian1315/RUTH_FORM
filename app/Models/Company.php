<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        "owner",
        "ifu",
        "denomination",
        "form_juridique",
        "creation_date",
        "phone",
        "email",
        "departement",
        "adresse",
        "rccm",
        "principal_activity",
        "activity_area"
    ];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function consulars(): HasMany
    {
        return $this->hasMany(CompanyConsular::class, "elected_consular")->with(["consular"]);
    }

    // function company(): BelongsTo
    // {
    //     return $this->belongsTo(Company::class, "company_id");
    // }

    function mandates(): HasMany
    {
        return $this->hasMany(ElectedConsularFonction::class, "mandate");
    }
}
