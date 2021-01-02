<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ShoppingListResource;
use App\Models\ShoppingList;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class ShoppingListApiController extends CrudApiController
{

    protected array $rules = [
        'name'           => 'required|string',
        'total_calories' => 'required|numeric|between:0.01,9999.99',
        'total_price'    => 'required|numeric|between:0.01,9999.99',
    ];

    public function __construct(
        ResponseFactory $responseFactory,
        ShoppingList $model,
        ValidationFactory $validationFactory
    ) {
        parent::__construct($responseFactory, $model, $validationFactory, ShoppingListResource::class);
    }

}
