<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\AbstractPaginator;

abstract class CrudApiController extends Controller
{

    protected array $rules = [];

    protected bool $updateSometimes = true;

    private ResponseFactory $responseFactory;

    private Model $model;

    private string $resourceClass;

    private ValidationFactory $validationFactory;

    protected function __construct(
        ResponseFactory $responseFactory,
        Model $model,
        ValidationFactory $validationFactory,
        string $resourceClass
    ) {
        $this->responseFactory = $responseFactory;
        $this->model = $model;
        $this->validationFactory = $validationFactory;
        $this->resourceClass = $resourceClass;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse|Response
     */
    public function index()
    {
        $model = $this->model->paginate();

        if (!$model) {
            return $this->responseFactory->noContent();
        }

        return $this->paginatorResource($model)
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
        $validate = $this->validationFactory->make($request->all(), $this->rules);

        foreach ($validate->errors()
                          ->all() as $message) {
            return $this->responseFactory->badRequest($message);
        }

        $model = $this->model->create($request->all());

        if (!$model) {
            return $this->responseFactory->badRequest('No se logro crear el objeto correctamente.');
        }

        return $this->makeResource($model->refresh())
                    ->response();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return JsonResponse|Response
     */
    public function show(int $id)
    {
        $model = $this->model->find($id);

        if (!$model) {
            return $this->responseFactory->notFound();
        }

        return $this->makeResource($model)
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
    public function update(Request $request, int $id)
    {
        if ($this->updateSometimes) {
            $rules = [];
            foreach ($this->rules as $key => $value) {
                $rules[$key] = "sometimes|$value";
            }
        }

        $validate = $this->validationFactory->make($request->all(), $rules ?? $this->rules);

        foreach ($validate->errors()
                          ->all() as $message) {
            return $this->responseFactory->badRequest($message);
        }

        $model = $this->model->find($id);

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
        $model = $this->model->find($id);

        if (!$model) {
            return $this->responseFactory->notFound();
        }

        if ($model->hasShoppingList()) {
            return $this->responseFactory->badRequest('No se puede eliminar un registro que esta incluido en una lista.');
        }

        if ($model->delete()) {
            return $this->responseFactory->noContent();
        }

        return $this->responseFactory->badRequest('No se logro eliminar el objeto correctamente.');
    }

    /**
     * @param  Model  $model
     *
     * @return JsonResource
     */
    private function makeResource(Model $model)
    {
        return $this->resourceClass::make($model);
    }

    /**
     * @param  AbstractPaginator  $model
     *
     * @return ResourceCollection
     */
    private function paginatorResource(AbstractPaginator $model)
    {
        return $this->resourceClass::collection($model);
    }
}
