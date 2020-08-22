<?php

namespace App\Models;

use App\Traits\hasChildren;
use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use hasChildren, Orderable;

    protected $fillable = [
        'name', 'slug', 'order'
    ];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
