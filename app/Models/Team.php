<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description"
    ];

    function belong_to_admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, "admin")->with("belong_to_organisation");
    }

    function belong_to_organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, "organisation");
    }

    function members(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, "members_teams", "team_id", "member_id")->with("belong_to_organisation");
    }
}
