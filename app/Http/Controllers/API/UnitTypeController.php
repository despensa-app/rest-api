<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UnitTypeResource;
use App\Models\UnitType;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UnitTypeController extends Controller
{

    /**
     * @var ResponseFactory
     */
    private ResponseFactory $responseFactory;

    /**
     * @var UnitType
     */
    private UnitType $unitType;

    /**
     * UnitTypeController constructor.
     *
     * @param  ResponseFactory  $responseFactory
     * @param  UnitType  $unitType
     */
    public function __construct(ResponseFactory $responseFactory, UnitType $unitType)
    {
        $this->responseFactory = $responseFactory;
        $this->unitType = $unitType;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function index()
    {
        $model = $this->unitType->paginate();

        if (!$model) {
            return $this->responseFactory->noContent();
        }

        return UnitTypeResource::collection($model)
                               ->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        foreach ($validate->errors()
                          ->all() as $message) {
            return $this->responseFactory->badRequest($message);
        }

        $model = $this->unitType->create($request->all());

        if (!$model) {
            return $this->responseFactory->badRequest('No se logro crear el objeto correctamente.');
        }

        return UnitTypeResource::make($model->refresh())
                               ->response();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function show($id)
    {
        $model = $this->unitType->find($id);

        if (!$model) {
            return $this->responseFactory->noContent();
        }

        return UnitTypeResource::make($model)
                               ->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'sometimes|required|string',
        ]);

        foreach ($validate->errors()
                          ->all() as $message) {
            return $this->responseFactory->badRequest($message);
        }

        $model = $this->unitType->find($id);

        if (!$model) {
            return $this->responseFactory->notFound();
        }

        $model->fill($request->all());

        if (!$model->save()) {
            return $this->responseFactory->badRequest('No se logro actualizar el objeto correctamente.');
        }

        return $this->responseFactory->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = $this->unitType->find($id);

        if (!$model) {
            return $this->responseFactory->badRequest('El registro no existe.');
        }

        if ($model->hasShoppingList()) {
            return $this->responseFactory->badRequest('No se puede eliminar un registro que esta incluido en una lista.');
        }

        if ($model->delete()) {
            return $this->responseFactory->noContent();
        }

        return $this->responseFactory->badRequest('No se logro eliminar el objeto correctamente.');
    }
}
