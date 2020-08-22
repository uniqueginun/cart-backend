<?php

namespace App\Models;

use App\Cart\Money;
use App\Models\Collections\ProductVariationCollection;
use Illuminate\Database\Eloquent\Model;
use App\Traits\hasPrice;

class ProductVariation extends Model
{
    use hasPrice;

    public function getPriceAttribute($value)
    {
        return $value !== 0 ? new Money($value) : $this->product->price;
    }

    public function priceVaries()
    {
        return $this->price->amount() !== $this->product->price->amount();
    }

    public function minStock($count)
    {
        return min($this->stockCount(), $count);
    }

    public function stockCount()
    {
        return $this->stock->sum('pivot.stock');
    }

    public function inStock()
    {
        return $this->stockCount() > 0;
    }

    public function type()
    {
        return $this->hasOne(ProductVariationType::class, 'id', 'product_variation_type_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function stock()
    {
        return $this->belongsToMany(
            ProductVariation::class, 'product_variation_stock_view'
        )->withPivot(['stock', 'in_stock']);
    }

    public function newCollection(array $models = [])
    {
        return new ProductVariationCollection($models);
    }
}
