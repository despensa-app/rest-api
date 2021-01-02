<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProductHasShoppingList
 * @property int $units_per_product
 * @property string $total_calories Total de calorías por unidad.
 * @property string $total_price Precio total por unidad.
 * @property int $product_id
 * @property int $shopping_list_id
 * @property int $unit_type_id
 */
class ProductHasShoppingList extends Model
{

    use HasFactory;

    protected $table = 'products_has_shopping_list';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $guarded = [];

}
