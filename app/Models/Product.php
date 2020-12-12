<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Product
 * @property int $id
 * @property string $name
 * @property double $price
 * @property string $img_url
 * @property double $calories
 * @property string|null $description Descripción del producto, notas, etc.
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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

}
