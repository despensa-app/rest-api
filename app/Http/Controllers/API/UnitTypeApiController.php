<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\UnitTypeResource;
use App\Models\UnitType;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Validation\Factory as ValidationFactory;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UnitTypeApiController extends CrudApiController
{

    protected array $rules = ['name' => 'required|string'];

    public function __construct(
        ResponseFactory $responseFactory,
        UnitType $unitType,
        ValidationFactory $validationFactory
    ) {
        parent::__construct($responseFactory, $unitType, $validationFactory, UnitTypeResource::class);
    }

    public function destroy($id)
    {
        return $this->destroyBase($id, function (UnitType $model) {
            if ($model->hasShoppingList()) {
                throw new BadRequestHttpException('No se puede eliminar un registro que esta incluido en una lista.');
            }
        });
    }

}
