<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Product::class;
    public function definition()
    {
        return [
            //
            'brand_id' => 1,
            'category_id' => $this->faker->numberBetween(1, 3),
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'content' => $this->faker->text,
            'price' => $this->faker->numberBetween(100000, 1000000),
            'quantity' => $this->faker->numberBetween(10, 50),
            'qty_sold' => $this->faker->numberBetween(1, 50),
            'discount' => $this->faker->numberBetween(0.1, 0.4),
            'featured' => $this->faker->numberBetween(0, 1)
        ];
    }
}
