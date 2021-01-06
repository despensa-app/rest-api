<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductHasShoppingList;
use Closure;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductApiController extends CrudApiController
{

    protected array $rules = [
        'name'     => 'required|string',
        'price'    => 'required|numeric|between:0.01,9999.99',
        'img_url'  => 'required|url',
        'calories' => 'required|numeric|between:0.01,9999.99',
    ];

    public function __construct(
        ResponseFactory $responseFactory,
        Product $model,
        ValidationFactory $validationFactory
    ) {
        parent::__construct($responseFactory, $model, $validationFactory, ProductResource::class);
    }

    public function update(Request $request, int $id)
    {
        return $this->updateBase($request, $id, $this->callbackAfterSave());
    }

    public function destroy($id)
    {
        return $this->destroyBase($id, function (Product $model) {
            if ($model->hasShoppingList()) {
                throw new BadRequestHttpException('No se puede eliminar un registro que esta incluido en una lista.');
            }
        });
    }

    private function callbackAfterSave(): Closure
    {
        return function (Product $product) {
            $unique = static function (ProductHasShoppingList $productHasShoppingList) {
                return $productHasShoppingList->shopping_list_id;
            };

            $product->productShoppingList->each($this->updateTotalCaloriesAndPrice($product))
                                         ->unique($unique)
                                         ->each($this->updateShoppingList());
        };
    }

    private function updateTotalCaloriesAndPrice(Product $product): Closure
    {
        return static function (ProductHasShoppingList $productHasShoppingList) use ($product) {
            $productHasShoppingList->setTotalCaloriesAndPrice($product);

            if (!$productHasShoppingList->save()) {
                throw new HttpException(500,
                    'No se logro actualizar el total de calorías y precio por unidad en las listas de la compra.');
            }
        };
    }

    private function updateShoppingList(): Closure
    {
        return static function (ProductHasShoppingList $productHasShoppingList) {
            $shoppingList = $productHasShoppingList->shoppingList;
            $shoppingList->setTotalCaloriesAndPrice();

            if (!$shoppingList->save()) {
                throw new HttpException(500,
                    'No se logro actualizar el total de calorías y el precio total de todas las unidades en la lista de la compra.');
            }
        };
    }

}
