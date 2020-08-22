<?php


namespace App\Filters;


class ProductFilters extends Filters
{
    protected $filters = ['category'];

    public function category($categorySlug)
    {
        return $this->builder->whereHas('categories', function ($query) use ($categorySlug) {
           $query->where('slug', $categorySlug);
        });
    }
}