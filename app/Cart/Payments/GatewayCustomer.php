<?php


namespace App\Cart\Payments;


use App\Models\PaymentMethod;

interface GatewayCustomer
{
    public function charge(PaymentMethod $method, $amount);

    public function addCard($token);

    public function id();

}