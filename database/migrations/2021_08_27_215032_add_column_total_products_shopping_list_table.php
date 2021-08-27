<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTotalProductsShoppingListTable extends Migration
{

    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('shopping_list', function (Blueprint $table) {
            $table->unsignedInteger('total_products')
                  ->after('name')
                  ->default(0)
                  ->comment('Número total de productos.');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('shopping_list', function (Blueprint $table) {
            $table->dropColumn('total_products');
        });
    }
}
