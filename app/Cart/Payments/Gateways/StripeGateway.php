<?php


namespace App\Cart\Payments\Gateways;


use App\Cart\Payments\Gateway;
use App\Models\User;
use Stripe\Customer as stripeCustomer;

class StripeGateway implements Gateway
{

    protected $user;

    public function withUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function createCustomer()
    {
        if ($this->user->gateway_customer_id) {
            return $this->getCustomer();
        }

        $customer = new StripeGatewayCustomer($this, $this->createStripeCustomer());

        $this->user->update([
            'gateway_customer_id' => $customer->id()
        ]);

        return $customer;
    }

    public function user()
    {
        return $this->user;
    }

    public function getCustomer()
    {
        return new StripeGatewayCustomer(
            $this,
            StripeCustomer::retrieve($this->user->gateway_customer_id)
        );
    }

    protected function createStripeCustomer()
    {
        return stripeCustomer::create([
            'email' => $this->user->email
        ]);
    }
}