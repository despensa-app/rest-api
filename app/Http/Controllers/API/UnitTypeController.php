<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UnitTypeResource;
use App\Models\UnitType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UnitTypeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $model    = UnitType::paginate();
        $resource = UnitTypeResource::collection($model);

        return $resource->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
            return response()
                ->json([
                    'error' => [
                        'message' => 'No se encontraron registros.',
                    ],
                ])
                ->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $resource = new UnitTypeResource($model);

        return $resource->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        //
    }
}
