<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $guarded = [];
    // protected $fillable = [
    //     "owner",
    //     "house",
    //     "nature",
    //     "type",
    //     "loyer",
    //     "number",
    //     "comments",

    //     "photo",

    //     "gardiennage",
    //     "forfait_forage",

    //     "rubbish",
    //     "vidange",
    //     "cleaning",

    //     ##__EAU
    //     "water",
    //     "unit_price",

    //     "water_card_counter",
    //     "water_conventionnal_counter",
    //     "water_counter_start_index",
    //     "forfait_forage",
    //     "forage",

    //     ##__ELECTRICITY
    //     "electricity",
    //     "electricity_card_counter",
    //     "electricity_conventionnal_counter",
    //     "electricity_discounter",
    //     "electricity_counter_start_index",
    //     "electricity_counter_number",

    //     "total_amount",
    //     "visible",
    //     "delete_at"
    // ];

    function Owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function House(): BelongsTo
    {
        return $this->belongsTo(House::class, "house")->where(["visible"=>1])->with(["Proprietor"]);
    }

    function Nature(): BelongsTo
    {
        return $this->belongsTo(RoomNature::class, "nature");
    }

    function Type(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, "type");
    }

    function Locations(): HasMany
    {
        return $this->hasMany(Location::class, "room")->with(["Locataire", "House", "Room", "Type"]);
    }
}
