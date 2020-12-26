<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UnitTypeResource;
use App\Models\UnitType;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Validation\Factory as ValidationFactory;

class UnitTypeController extends CrudApiController
{

    protected array $rules = ['name' => 'required|string'];

    public function __construct(
        ResponseFactory $responseFactory,
        UnitType $unitType,
        ValidationFactory $validationFactory
    ) {
        parent::__construct($responseFactory, $unitType, $validationFactory, UnitTypeResource::class);
    }

}
