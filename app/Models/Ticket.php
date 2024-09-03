<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "member",
        "type",
        "priority",
        "tags",
        "status"
    ];

    function affected_to(): BelongsTo
    {
        return $this->belongsTo(Member::class, "member")->with(["belong_to_organisation", "teams", "belong_to_admin"]);
    }

    function status(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class, "status");
    }
}
