<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductHasShoppingList extends Model
{
    use HasFactory;

    protected $table = 'products_has_shopping_list';
}
