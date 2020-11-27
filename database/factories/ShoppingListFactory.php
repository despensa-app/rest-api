<?php

namespace Database\Factories;

use App\Models\ShoppingList;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShoppingListFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     * @var string
     */
    protected $model = ShoppingList::class;

    /**
     * Define the model's default state.
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
