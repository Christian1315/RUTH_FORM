<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyConsular extends Model
{
    use HasFactory;
    protected $table = "companies_elected_consulars";

    protected $fillable = [
        "elected_consular",
        "fonction_id",
        "company_id",
        "mandate_id",
    ];

    protected $hidden = [
        "company_id",
        "elected_consular",
        "fonction_id",
        "mandate_id",
        "id",
        "created_at",
        "updated_at",
    ];

    function consular(): BelongsTo
    {
        return $this->belongsTo(ElectedConsular::class, "elected_consular")->with(["postes"]);
    }
    function fonction(): BelongsTo
    {
        return $this->belongsTo(Fonction::class, "fonction_id");
    }

    function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, "company_id");
    }

    function mandate(): BelongsTo
    {
        return $this->belongsTo(Mandate::class, "mandate_id")->with(["poste"]);
    }
}
