<?php

namespace App\Traits;

use App\Cart\Money;

trait hasPrice
{
    public function getPriceAttribute($value)
    {
        return new Money($value);
    }

    public function getFormattedPriceAttribute()
    {
        return $this->price->formatted();
    }
}