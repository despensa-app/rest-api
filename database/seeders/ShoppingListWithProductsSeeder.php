<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ShoppingList;
use App\Models\UnitType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShoppingListWithProductsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $shoppingListCount = ShoppingList::query()
                                         ->count();
        $shoppingListRand = $this->getRand(ShoppingList::query(), random_int(0, $shoppingListCount));

        foreach ($shoppingListRand as $shoppingList) {
            $totalCalories = 0;
            $totalPrice = 0;
            $unitTypeCount = UnitType::query()
                                     ->count();
            $productCount = Product::query()
                                   ->count();
            $recordLimit = min(random_int(1, 10), $unitTypeCount, $productCount);

            $unitTypes = $this->getRand(UnitType::query(), $recordLimit);
            $products = $this->getRand(Product::query(), $recordLimit);

            for ($i = 0; $i < $recordLimit; $i++) {
                $unitPerProduct = random_int(1, 10);
                $product = $products->shift();
                $unitType = $unitTypes->shift();
                $totalCaloriesPerUnit = $product->calories * $unitPerProduct;
                $totalPricePerUnit = $product->price * $unitPerProduct;
                $totalCalories += $totalCaloriesPerUnit;
                $totalPrice += $totalPricePerUnit;

                DB::table('products_has_shopping_list')
                  ->insert([
                      'units_per_product' => $unitPerProduct,
                      'total_calories'    => $totalCaloriesPerUnit,
                      'total_price'       => $totalPricePerUnit,
                      'product_id'        => $product->id,
                      'shopping_list_id'  => $shoppingList->id,
                      'unit_type_id'      => $unitType->id,
                  ]);
            }

            $shoppingList->total_calories = $totalCalories;
            $shoppingList->total_price = $totalPrice;
            $shoppingList->save();
        }
    }

    /**
     * @param  Builder  $builder
     * @param  int  $limit
     *
     * @return Builder[]|Collection
     */
    private function getRand(Builder $builder, int $limit)
    {
        return $builder->orderByRaw('rand()')
                       ->limit($limit)
                       ->get();
    }

}
