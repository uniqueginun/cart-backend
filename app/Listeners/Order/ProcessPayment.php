<?php


namespace App\Listeners\Order;

use App\Cart\Payments\Gateway;
use App\Events\Order\OrderCreated;
use App\Events\Order\OrderPaid;
use App\Events\Order\OrderPaymentFailed;
use App\Exceptions\PaymentFeiledException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessPayment
{
    /**
     * @var Gateway
     */

    protected $gateway;

    public function __construct(Gateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Handle the event.
     *
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        try {

            $this->gateway->withUser($order->user)
                ->getCustomer()
                ->charge($order->paymentMethod, $order->total()->amount());

            event(new OrderPaid($order));

        } catch (PaymentFeiledException $exception) {
            event(new OrderPaymentFailed($order));
        }
    }
}