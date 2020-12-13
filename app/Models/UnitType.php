<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method UnitType find(int $id)
 * @method Paginator paginate()
 */
class UnitType extends Model
{

    use HasFactory;

    protected $table = 'unit_types';

    protected $fillable = ['name'];

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    public function hasShoppingList(): bool
    {
        $count = ProductHasShoppingList::query()
                                       ->where('unit_type_id', '=', $this->id)
                                       ->count();

        return $count > 0;
    }
}
