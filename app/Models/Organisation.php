<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organisation extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "img",
        "sigle",
    ];

    function admins(): HasMany
    {
        return $this->hasMany(Admin::class, "organisation")->with(["teams"]);
    }
}
