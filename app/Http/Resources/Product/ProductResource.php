<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'seller_id' => $this->user_id,
            'description' => $this->detail,
            'price' => $this->price,
            'stock' => $this->stock == 0 ? 'Out of Stock' : $this->stock,
            'discount' => $this->discount,
            'totalPrice' => round((1 - ($this->discount/100)) * $this->price,2),
            'rating' => $this->reviews->count() >0 ?round($this->reviews->sum('star')/$this->reviews->count()) : 'No Rating',
            'href' => [
                'review' => route('reviews.index',$this->id)
            ]
        ];
    }
}
