<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mandate extends Model
{
    use HasFactory;

    protected $fillable = [
        "owner",
        "code",
        "start_date",
        "end_date",
        "designation"
    ];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function poste(): HasOne
    {
        return $this->hasOne(ConsularPoste::class, "poste_id")->with(["poste"]);
    }
}
