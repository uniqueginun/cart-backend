<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\Builder;

trait Filterable
{

    public function scopeFilter(Builder $builder, $filters = [])
    {
        return $filters->apply($builder);
    }
}