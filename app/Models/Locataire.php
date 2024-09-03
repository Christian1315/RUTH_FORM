<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Locataire extends Model
{
    use HasFactory;

    protected  $guarded = [];
    // protected $fillable = [
    //     "agency",
    //     "owner",
    //     "email",
    //     "sexe",
    //     "prenom",
    //     "phone",
    //     "piece_number",
    //     "mandate_contrat",
    //     "comments",
    //     "name",
    //     "adresse",
    //     "card_id",
    //     "card_type",
    //     "departement",
    //     "country",
    //     "prorata",
    //     "prorata_date",
    //     "kilowater_price",
    //     "discounter"
    // ];

    function _Agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, "agency");
    }

    function Owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function CardType(): BelongsTo
    {
        return $this->belongsTo(CardType::class, "card_type");
    }

    function Departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, "departement");
    }

    function Country(): BelongsTo
    {
        return $this->belongsTo(Country::class, "country");
    }

    function Locations(): HasMany
    {
        return $this->hasMany(Location::class, "locataire")->with(["Owner", "House", "Locataire", "Type", "Room", "Factures"]);
    }
}
