<?php

namespace App\Models;

use App\Traits\hasPrice;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use hasPrice;

    public function countries()
    {
        return $this->belongsToMany(Country::class);
    }
}
