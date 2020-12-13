<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    /**
     * @var ResponseFactory
     */
    private ResponseFactory $responseFactory;

    /**
     * @var Product
     */
    private Product $product;

    /**
     * ProductController constructor.
     *
     * @param  ResponseFactory  $responseFactory
     * @param  Product  $product
     */
    public function __construct(ResponseFactory $responseFactory, Product $product)
    {
        $this->responseFactory = $responseFactory;
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse|Response
     */
    public function index()
    {
        $model = $this->product->paginate();

        if (!$model) {
            return $this->responseFactory->noContent();
        }

        return ProductResource::collection($model)
                              ->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return JsonResponse|Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name'     => 'required|string',
            'price'    => 'required|numeric|between:0.01,9999.99',
            'img_url'  => 'required|url',
            'calories' => 'required|numeric|between:0.01,9999.99',
        ]);

        foreach ($validate->errors()
                          ->all() as $message) {
            return $this->responseFactory->badRequest($message);
        }

        $model = $this->product->create($request->all());

        if (!$model) {
            return $this->responseFactory->badRequest('No se logro crear el objeto correctamente.');
        }

        return ProductResource::make($model->refresh())
                              ->response();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return JsonResponse|Response
     */
    public function show($id)
    {
        $model = $this->product->find($id);

        if (!$model) {
            return $this->responseFactory->noContent();
        }

        return ProductResource::make($model)
                              ->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name'     => 'sometimes|required|string',
            'price'    => 'sometimes|required|numeric|between:0.01,9999.99',
            'img_url'  => 'sometimes|required|url',
            'calories' => 'sometimes|required|numeric|between:0.01,9999.99',
        ]);

        foreach ($validate->errors()
                          ->all() as $message) {
            return $this->responseFactory->badRequest($message);
        }

        $model = $this->product->find($id);

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
     * @return Response
     */
    public function destroy($id)
    {
        $model = $this->product->find($id);

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
