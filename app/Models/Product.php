<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Product
 * @property int $id
 * @property string $name
 * @property float $price
 * @property string $img_url
 * @property int $calories
 * @property string $description Descripción del producto, notas, etc.
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

}
