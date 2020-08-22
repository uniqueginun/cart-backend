<?php

namespace App\Models;

use App\Traits\hasPrice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;

class Product extends Model
{

    use Filterable, hasPrice;

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function stockCount()
    {
        return $this->variations->sum(function ($variation) {
            return $variation->stockCount();
        });
    }

    public function inStock()
    {
        return $this->stockCount() > 0;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class)->orderBy('order', 'asc');
    }

}
