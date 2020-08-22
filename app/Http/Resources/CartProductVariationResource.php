<?php

namespace App\Http\Resources;

use App\Cart\Money;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProductVariationResource extends ProductVariationResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $total = new Money($this->pivot->quantity * $this->price->amount());

        return array_merge(parent::toArray($request), [
            'product' => new ProductIndexResource($this->product),
            'quantity' => $this->pivot->quantity,
            'total' => $total->formatted()
        ]);
    }
}
