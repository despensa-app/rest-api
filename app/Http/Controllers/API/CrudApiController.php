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
use Laravel\Scout\Builder;
use Laravel\Scout\Searchable;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class CrudApiController extends Controller
{

    protected array $rules = [];

    protected bool $updateSometimes = true;

    protected ResponseFactory $responseFactory;

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
     *
     * @param  Request  $request
     *
     * @return JsonResponse|Response
     */
    public function index(Request $request)
    {
        $model = $this->search($request, $this->model);

        if ($model->get()
                  ->isEmpty()) {
            return $this->responseFactory->noContent();
        }

        $model = $this->orderById($model);
        $model = $model->paginate()
                       ->withQueryString();

        if (!$model->count()) {
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
            throw new BadRequestHttpException($message);
        }

        $model = $this->model->create($request->all());

        if (!$model) {
            throw new BadRequestHttpException('No se logro crear el objeto correctamente.');
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

        $this->checkIfModelIsNull($model);

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
        return $this->updateBase($request, $id);
    }

    protected function updateBase(Request $request, int $id, \Closure $callbackAfterSave = null): Response
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
            throw new BadRequestHttpException($message);
        }

        $model = $this->model->find($id);

        $this->checkIfModelIsNull($model);
        $model->fill($request->all());

        if (!$model->save()) {
            throw new BadRequestHttpException('No se logro actualizar el objeto correctamente.');
        }

        if (is_callable($callbackAfterSave)) {
            $callbackAfterSave($model);
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
        return $this->destroyBase($id);
    }

    protected function destroyBase($id, \Closure $callbackBeforeDelete = null): Response
    {
        $model = $this->model->find($id);

        $this->checkIfModelIsNull($model);

        if (is_callable($callbackBeforeDelete)) {
            $callbackBeforeDelete($model);
        }

        if ($model->delete()) {
            return $this->responseFactory->noContent();
        }

        throw new BadRequestHttpException('No se logro eliminar el objeto correctamente.');
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

    private function checkIfModelIsNull(?Model $model, string $message = 'No se encontraron registros.'): void
    {
        if (!$model) {
            throw new NotFoundHttpException($message);
        }
    }

    private function search(Request $request, Model $model)
    {
        $searchQuery = $request->input("query");

        if ($searchQuery && $this->isUseTrait($model, Searchable::class)) {
            return $model->search($searchQuery);
        }

        return $model;
    }

    /**
     * @param  Model|Builder  $model
     *
     * @return Model
     */
    private function orderById($model)
    {
        $keyName = $model->model ? $model->model->getKeyName() : $model->getKeyName();

        if ("id" !== $keyName) {
            return $model;
        }

        return $model->orderBy('id', 'desc');
    }

    private function isUseTrait($object, string $classTrait, bool $recursive = true): bool
    {
        $classUses = class_uses($object);
        $result = array_key_exists($classTrait, $classUses);

        if ($result || !$recursive) {
            return $result;
        }

        $objectClass = is_object($object) ? get_class($object) : $object;

        try {
            $reflection = new \ReflectionClass($objectClass);

            if ($parentClass = $reflection->getParentClass()) {
                $result = $this->isUseTrait($parentClass->getName(), $classTrait);
            }

            if (!$result) {
                foreach ($classUses as $class) {
                    if ($result = $this->isUseTrait($class, $classTrait)) {
                        break;
                    }
                }
            }
        } catch (\Exception $exception) {
            $result = false;
        }

        return $result;
    }
}
