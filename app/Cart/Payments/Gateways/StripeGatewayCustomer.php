<?php


namespace App\Cart\Payments\Gateways;


use App\Cart\Payments\Gateway;
use App\Cart\Payments\GatewayCustomer;
use App\Exceptions\PaymentFeiledException;
use App\Models\PaymentMethod;
use Stripe\Customer as stripeCustomer;
use Stripe\Charge as stripeCharge;

class StripeGatewayCustomer implements GatewayCustomer
{

    /**
     * @var Gateway
     */
    protected $gateway;
    /**
     * @var stripeCustomer
     */
    protected $customer;

    public function __construct(Gateway $gateway, stripeCustomer $customer)
    {
        $this->gateway = $gateway;
        $this->customer = $customer;
    }

    public function charge(PaymentMethod $card, $amount)
    {
        try {
            stripeCharge::create([
                'currency' => 'gbp',
                'amount' => $amount,
                'customer' => $this->customer->id,
                'source' => $card->provider_id
            ]);
        } catch (\Exception $exception) {
            throw new PaymentFeiledException();
        }
    }

    public function addCard($token)
    {
        $card = $this->customer->sources->create([
            'source' => $token
        ]);

        $this->customer->default_source = $card->id;
        $this->customer->save();

        return $this->gateway->user()->paymentMethods()->create([
            'provider_id' => $card->id,
            'card_type' => $card->brand,
            'last_four' => $card->last4,
            'default' => true
        ]);
    }

    public function id()
    {
        return $this->customer->id;
    }
}