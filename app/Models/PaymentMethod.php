<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'user_id', 'card_type', 'last_four', 'default', 'provider_id'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($paymentMethod) {
            if($paymentMethod->default) {
                $paymentMethod->user->paymentMethods()->update([
                    'default' => false
                ]);
            }
        });
    }

    public function setDefaultAttribute($value)
    {
        $this->attributes['default'] = ($value == 'true' || $value) ? true : false;
    }

    public function getDefaultAttribute($value)
    {
        return ((int) $value === 1) ? true : false;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
