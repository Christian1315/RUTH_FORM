<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "email",
        "phone",
        "organisation",
    ];

    function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner")->where("is_super_admin", 0);
    }

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function as_user(): BelongsTo
    {
        return $this->belongsTo(User::class, "as_user");
    }

    function belong_to_organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, "organisation");
    }

    function teams() : HasMany {
        return $this->hasMany(Team::class,"admin");
    }
}
