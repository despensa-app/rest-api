<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ProductHasShoppingListResource;
use App\Http\Resources\ShoppingListResource;
use App\Models\ProductHasShoppingList;
use App\Models\ShoppingList;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class ShoppingListApiController extends CrudApiController
{

    protected array $rules = [
        'name' => 'required|string',
    ];

    public function __construct(
        ResponseFactory $responseFactory,
        ShoppingList $model,
        ValidationFactory $validationFactory
    ) {
        parent::__construct($responseFactory, $model, $validationFactory, ShoppingListResource::class);
    }

    public function products(int $id)
    {
        $model = ProductHasShoppingList::where('shopping_list_id', $id)
                                       ->paginate();

        if (!$model) {
            return $this->responseFactory->noContent();
        }

        return ProductHasShoppingListResource::collection($model)
                                             ->response();
    }

}
