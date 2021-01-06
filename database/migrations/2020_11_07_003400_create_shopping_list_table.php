<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingListTable extends Migration
{

    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_list', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedDecimal('total_calories', 11)
                  ->default(0)
                  ->comment('Total de calorías de todos los productos.');
            $table->unsignedDecimal('total_price', 11)
                  ->default(0)
                  ->comment('Precio total de todos los productos.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopping_list');
    }
}
