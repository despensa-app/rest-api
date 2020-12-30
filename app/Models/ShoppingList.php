<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ShoppingList
 *
 * @property int $id
 * @property string $name
 * @property float $total_calories Total de calorías de todos los productos.
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class ShoppingList extends Model
{

    use HasFactory;

    protected $table = 'shopping_list';

    protected $fillable = [
        'name',
        'total_calories',
    ];

    protected $casts = [
        'total_calories' => 'double',
        'created_at'     => 'timestamp',
        'updated_at'     => 'timestamp',
    ];
}
