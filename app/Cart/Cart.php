<?php


namespace App\Cart;


use App\Models\ShippingMethod;
use App\Models\User;

class Cart
{
    /**
     * @var User
     */
    protected $user;

    protected $changed = false;

    protected $shipping = null;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function withShipping($shipping)
    {
        $this->shipping = ShippingMethod::find($shipping);
        return $this;
    }

    public function products()
    {
        return $this->user->cart;
    }

    public function add($products)
    {
        $products = $this->getRequestProducts($products);

        return $this->user->cart()->syncWithoutDetaching($products);
    }

    public function update($id, $quantity)
    {
        return $this->user->cart()->updateExistingPivot($id, [
            'quantity' => $quantity
        ]);
    }

    public function delete($id)
    {
        return $this->user->cart()->detach($id);
    }

    public function empty()
    {
        return $this->user->cart()->detach();
    }

    public function isEmpty()
    {
        return $this->user->cart->sum('pivot.quantity') <= 0;
    }

    public function subtotal()
    {
        $subtotal = $this->user->cart->sum(function ($product) {
            return $product->price->amount() * $product->pivot->quantity;
        });

        return new Money($subtotal);
    }

    public function total()
    {
        if ($this->shipping) {
            return $this->subtotal()->add($this->shipping->price);
        }

        return $this->subtotal();
    }

    public function sync()
    {
        $this->user->cart->each(function ($product) {
            $availableQuantity = $product->minStock($product->pivot->quantity);

             if($availableQuantity != $product->pivot->quantity) {
                 $this->changed = true;
             }

            $product->pivot->update([
                'quantity' => $availableQuantity
            ]);
        });
    }

    public function hasChanged()
    {
        return $this->changed;
    }

    /**
     * @return mixed
     */
    private function getRequestProducts($products)
    {
        return collect($products)->keyBy('id')->map(function ($product) {
            return [
                "quantity" => $product['quantity'] + $this->getCurrentQuantity($product['id'])
            ];
        })->toArray();
    }

    protected function getCurrentQuantity($id)
    {
        if ($product = $this->user->cart->where('id', $id)->first()) {
            return $product->pivot->quantity;
        }
        return 0;
    }
}