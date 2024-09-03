<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";

    protected $fillable = [
        "img",
        "name",
        "description",
        "sale_price",
        "sale_tax",
        "price",
        "inner_reference",
        "bar_code",

        "can_be_sale",
        "can_be_buy",

        "category",
        "type",
        "owner"
    ];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, "category");
    }

    function type(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, "type");
    }

    function inventory(): BelongsTo
    {
        return $this->belongsTo(ProductInventory::class, "product");
    }
}
