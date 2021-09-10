<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductHasShoppingList;
use App\Models\ShoppingList;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidationFactory;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductShoppingListApiController extends Controller
{
    
    private ValidationFactory $validationFactory;
    
    private array $rules = [
            'units_per_product' => 'sometimes|required|numeric|between:1,99',
            'product_id'        => 'required|numeric',
            'shopping_list_id'  => 'required|numeric',
            'unit_type_id'      => 'required|numeric',
    ];
    
    private ProductHasShoppingList $model;
    
    private ResponseFactory $responseFactory;
    
    public function __construct(
            ResponseFactory $responseFactory,
            ProductHasShoppingList $model,
            ValidationFactory $validationFactory
    ) {
        $this->responseFactory = $responseFactory;
        $this->model = $model;
        $this->validationFactory = $validationFactory;
    }
    
    public function store(Request $request)
    {
        $this->validateStoreRequest($request);
        $model = $this->model->fill($request->all());
        $this->checkModelDoesntExists($model);
        
        if ($model->productUnitTypeShoppingListExists()) {
            throw new BadRequestHttpException('El producto ya existe en la lista.');
        }
        
        $model->setTotalCaloriesAndPrice();
        
        if (!$model->save()) {
            throw new BadRequestHttpException('No se logro crear el objeto correctamente.');
        }
        
        return $this->responseFactory->noContent();
    }
    
    public function update(Request $request)
    {
        $this->validateStoreRequest($request);
        $model = $this->model->fill($request->all());
        $this->checkModelDoesntExists($model);
        
        $currentDataModel = $model->find();
        
        if (!$currentDataModel) {
            throw new NotFoundHttpException('No se encontraron registros.');
        }
        
        if (!empty($model->units_per_product)) {
            $currentDataModel->units_per_product = $model->units_per_product;
        }
        
        if (!is_null($model->selected)) {
            $currentDataModel->selected = $model->selected;
        }
        
        $currentDataModel->setTotalCaloriesAndPrice();
        
        if (!$currentDataModel->save()) {
            throw new BadRequestHttpException('No se logro actualizar el objeto correctamente.');
        }
        
        $this->setTotalCaloriesAndPrice($currentDataModel->shoppingList);
        
        return $this->responseFactory->noContent();
    }
    
    public function destroy(Request $request)
    {
        $this->validateStoreRequest($request);
        
        $model = $this->model->fill($request->all())
                             ->find();
        
        if (!$model) {
            throw new NotFoundHttpException('No se encontraron registros.');
        }
        
        if (!$model->delete()) {
            throw new BadRequestHttpException('No se logro eliminar el objeto correctamente.');
        }
        
        $this->setTotalCaloriesAndPrice($model->shoppingList);
        
        return $this->responseFactory->noContent();
    }
    
    /**
     * @param  ShoppingList  $shoppingList
     */
    private function setTotalCaloriesAndPrice(ShoppingList $shoppingList): void
    {
        $shoppingList->setTotalCaloriesAndPrice();
        
        if (!$shoppingList->save()) {
            throw new HttpException(500,
                    'No se logro actualizar el total de calorías y el precio total de todas las unidades en la lista de la compra.');
        }
    }
    
    private function validateStoreRequest(Request $request): void
    {
        $validate = $this->validationFactory->make($request->all(), $this->rules);
        
        foreach ($validate->errors()
                          ->all() as $message) {
            throw new BadRequestHttpException($message);
        }
    }
    
    private function checkModelDoesntExists(ProductHasShoppingList $model): void
    {
        if ($model->productDoesntExists()) {
            throw new BadRequestHttpException('El producto no existe.');
        }
        
        if ($model->unitTypeDoesntExists()) {
            throw new BadRequestHttpException('El tipo de unidad no existe.');
        }
        
        if ($model->shoppingListDoesntExists()) {
            throw new BadRequestHttpException('El producto ya existe en la lista.');
        }
    }
    
}
