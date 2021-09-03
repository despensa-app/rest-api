<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSelectedToProductsHasShoppingList extends Migration
{

    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('products_has_shopping_list', function (Blueprint $table) {
            $table->boolean('selected')
                  ->default(false)
                  ->comment('Establece si el producto esta seleccionado.');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('products_has_shopping_list', function (Blueprint $table) {
            $table->dropColumn('selected');
        });
    }
}
