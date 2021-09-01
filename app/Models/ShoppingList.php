<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

/**
 * App\Models\ShoppingList
 * @property int $id
 * @property string $name
 * @property float $total_calories Total de calorías de todos los productos.
 * @property float $total_price Precio total de todos los productos.
 * @property float $total_products Número total de productos.
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property ProductHasShoppingList[]|Collection productShoppingList
 * @method ShoppingList find(int $id)
 */
class ShoppingList extends Model
{

    use HasFactory, Searchable;

    protected $table = 'shopping_list';

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'total_calories' => 'double',
        'total_price'    => 'double',
        'created_at'     => 'timestamp',
        'updated_at'     => 'timestamp',
    ];

    public function setTotalCaloriesAndPrice()
    {
        $this->total_calories = 0;
        $this->total_price = 0;
        $this->total_products = $this->productShoppingList->count();

        $this->productShoppingList->each(function (ProductHasShoppingList $productHasShoppingList) {
            $this->total_calories += $productHasShoppingList->total_calories;
            $this->total_price += $productHasShoppingList->total_price;
        });
    }

    public function productShoppingList(): HasMany
    {
        return $this->hasMany(ProductHasShoppingList::class, 'shopping_list_id');
    }
}
