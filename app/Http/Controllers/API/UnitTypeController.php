<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UnitTypeResource;
use App\Models\UnitType;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UnitTypeController extends Controller
{

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function index()
    {
        $model = UnitType::paginate();

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
        //TODO: validator:make
        $model = UnitType::create($request->all());

        if (!$model) {
            return $this->responseFactory->badRequest('No se logro crear el objeto correctamente.');
        }

        return UnitTypeResource::make($model)
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
        $model = UnitType::find($id);

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
        $model = UnitType::find($id);

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
        if (UnitType::destroy($id)) {
            return $this->responseFactory->noContent();
        }

        return $this->responseFactory->badRequest('No se logro eliminar el objeto correctamente.');
    }
}
