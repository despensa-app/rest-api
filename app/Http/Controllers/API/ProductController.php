<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        $model = Product::paginate();

        if (!$model) {
            return $this->responseFactory->noContent();
        }

        return ProductResource::collection($model)
                              ->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name'        => 'required|string',
            'price'       => 'required|numeric|between:0.01,9999.99',
            'img_url'     => 'required|url',
            'calories'    => 'required|numeric|between:0.01,9999.99',
        ]);

        foreach ($validate->errors()
                          ->all() as $message) {
            return $this->responseFactory->badRequest($message);
        }

        $model = Product::create($request->all());

        if (!$model) {
            return $this->responseFactory->badRequest('No se logro crear el objeto correctamente.');
        }

        return ProductResource::make($model)
                              ->response();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Product::find($id);

        if (!$model) {
            return $this->responseFactory->noContent();
        }

        return ProductResource::make($model)
                              ->response();
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
        $validate = Validator::make($request->all(), [
            'name'        => 'sometimes|required|string',
            'price'       => 'sometimes|required|numeric|between:0.01,9999.99',
            'img_url'     => 'sometimes|required|url',
            'calories'    => 'sometimes|required|numeric|between:0.01,9999.99',
        ]);

        foreach ($validate->errors()
                          ->all() as $message) {
            return $this->responseFactory->badRequest($message);
        }

        $model = Product::find($id);

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
        if (Product::destroy($id)) {
            return $this->responseFactory->noContent();
        }

        return $this->responseFactory->badRequest('No se logro eliminar el objeto correctamente.');
    }
}
