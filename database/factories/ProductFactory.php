<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     * @return array
     */
    public function definition()
    {
        return [
            'name'        => $this->faker->word,
            'price'       => $this->faker->randomFloat(2, 0, 9999),
            'img_url'     => $this->faker->imageUrl(),
            'calories'    => $this->faker->randomFloat(2, 0, 9999),
            'description' => $this->faker->text,
        ];
    }
}
