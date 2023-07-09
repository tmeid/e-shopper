<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Address::class;
    public function definition()
    {
        return [
            //
           'address' => $this->faker->address

        ];
    }
}
