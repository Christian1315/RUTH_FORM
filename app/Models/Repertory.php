<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repertory extends Model
{
    use HasFactory;

    protected $fillable = [
        "firstname",
        "lastname",
        "ministry",
        "denomination",
        "residence",
        "commune",
        "contact",
        "owner",
        "present",
        "badge"
    ];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }
}
