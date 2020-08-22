<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\Builder;

trait hasChildren
{

    public function scopeParents(Builder $builder)
    {
        $builder->whereNull('parent_id');
    }
}