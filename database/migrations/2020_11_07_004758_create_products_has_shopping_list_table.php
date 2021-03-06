<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProductsHasShoppingListTable extends Migration
{

    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('products_has_shopping_list', function (Blueprint $table) {
            $table->unsignedTinyInteger('units_per_product')
                  ->default(1);
            $table->unsignedDecimal('total_calories', 11)
                  ->comment('Total de calorías por unidad.');
            $table->unsignedDecimal('total_price', 11)
                  ->comment('Precio total por unidad.');
            $table->foreignId('product_id')
                  ->index()
                  ->constrained('products');
            $table->foreignId('shopping_list_id')
                  ->index()
                  ->constrained('shopping_list');
            $table->foreignId('unit_type_id')
                  ->index()
                  ->constrained('unit_types');
            $table->primary([
                'product_id',
                'shopping_list_id',
                'unit_type_id',
            ], 'products_has_shopping_list_primary');
        });

        DB::statement("alter table products_has_shopping_list comment 'Relación de productos y lista de la compra.'");
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_has_shopping_list');
    }
}
