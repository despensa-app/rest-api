<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Product
 * @property int $id
 * @property string $name
 * @property double $price
 * @property string $img_url
 * @property double $calories
 * @property string|null $description Descripción del producto, notas, etc.
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property ProductHasShoppingList[]|Collection productShoppingList
 * @method Product find(int $id)
 * @method Paginator paginate()
 */
class Product extends Model
{

    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'products';

    protected $fillable = [
        'name',
        'price',
        'img_url',
        'calories',
        'description',
    ];

    protected $casts = [
        'price'      => 'double',
        'calories'   => 'double',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    public function hasShoppingList(): bool
    {
        $count = ProductHasShoppingList::query()
                                       ->where('product_id', '=', $this->id)
                                       ->count();

        return $count > 0;
    }

    public function productShoppingList(): HasMany
    {
        return $this->hasMany(ProductHasShoppingList::class, 'product_id');
    }

}
