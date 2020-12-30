<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class ProductApiController extends CrudApiController
{

    protected array $rules = [
        'name'     => 'required|string',
        'price'    => 'required|numeric|between:0.01,9999.99',
        'img_url'  => 'required|url',
        'calories' => 'required|numeric|between:0.01,9999.99',
    ];

    public function __construct(
        ResponseFactory $responseFactory,
        Product $model,
        ValidationFactory $validationFactory
    ) {
        parent::__construct($responseFactory, $model, $validationFactory, ProductResource::class);
    }

}
