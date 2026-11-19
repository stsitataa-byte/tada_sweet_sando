<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD

class Product extends Model
{
    protected $guarded = [];
=======
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'sold_quantity',
        'image_url',
        'is_active',
        'short_name',
        'tagline',
        'image',
        'color',
        'accent_color',
        'header_color',
        'tasting_notes',
        'ingredients',
        'fruits',
        'nutrition',
        'calories',
        'rating',
        'detail_enabled',
        'sort_order',
    ];
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be

    protected function casts(): array
    {
        return [
<<<<<<< HEAD
            'detail_enabled' => 'boolean',
            'fruits' => 'array',
            'ingredients' => 'array',
            'nutrition' => 'array',
        ];
    }
}
=======
            'price' => 'decimal:2',
            'stock' => 'integer',
            'sold_quantity' => 'integer',
            'is_active' => 'boolean',
            'ingredients' => 'array',
            'fruits' => 'array',
            'nutrition' => 'array',
            'detail_enabled' => 'boolean',
            'calories' => 'integer',
            'rating' => 'integer',
        ];
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
