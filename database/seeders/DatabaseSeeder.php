<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ShoppingList;
use App\Models\UnitType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     * @return void
     */
    public function run()
    {
        UnitType::factory(10)
                ->create();
        Product::factory(10)
               ->create();
        ShoppingList::factory(10)
                    ->create();
    }
}
