<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ProductHasShoppingList
 * @property int $units_per_product
 * @property string $total_calories Total de calorías por unidad.
 * @property string $total_price Precio total por unidad.
 * @property int $product_id
 * @property int $shopping_list_id
 * @property int $unit_type_id
 * @property Product product
 */
class ProductHasShoppingList extends Model
{

    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indicates if the model's ID is auto-incrementing.
     * @var bool
     */
    public $incrementing = false;

    protected $table = 'products_has_shopping_list';

    protected $guarded = [
        'total_calories',
        'total_price',
    ];

    /**
     * The primary key associated with the table.
     */
    protected $primaryKeys = [
        'product_id',
        'shopping_list_id',
        'unit_type_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function find()
    {
        $query = $this->newQuery();
        $this->setPrimaryKeys($query);

        return $query->first();
    }

    public function productUnitTypeShoppingListExists(): bool
    {
        $query = $this->newQuery();
        $this->setPrimaryKeys($query);

        return $query->exists();
    }

    public function productDoesntExists(): bool
    {
        return $this->product === null;
    }

    public function unitTypeDoesntExists(): bool
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id') === null;
    }

    public function shoppingListDoesntExists(): bool
    {
        return $this->belongsTo(ShoppingList::class, 'shopping_list_id') === null;
    }

    public function setTotalCaloriesAndPrice(Product $product = null): void
    {
        $product = $product ?? $this->product;

        $this->total_calories = $product->calories * $this->units_per_product;
        $this->total_price = $product->price * $this->units_per_product;
    }

    /**
     * Set the keys for a save update query.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    protected function setKeysForSaveQuery($query)
    {
        $this->setPrimaryKeys($query);

        return $query;
    }

    /**
     * @param  Builder  $query
     */
    protected function setPrimaryKeys(Builder $query): void
    {
        foreach ($this->primaryKeys as $primaryKey) {
            $value = $this->original[$primaryKey] ?? $this->getAttribute($primaryKey);
            $query->where($primaryKey, '=', $value);
        }
    }

}
