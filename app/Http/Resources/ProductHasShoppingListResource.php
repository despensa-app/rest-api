<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\ShoppingList;
use App\Models\UnitType;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property UnitType unitType
 * @property ShoppingList shoppingList
 * @property Product product
 */
class ProductHasShoppingListResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['unit_type'] = UnitTypeResource::make($this->unitType);
        $data['shopping_list'] = ShoppingListResource::make($this->shoppingList);
        $data['product'] = ProductResource::make($this->product);

        return $data;
    }
}
