<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\Operation;
use Illuminate\Database\Eloquent\Factories\Factory;

class OperationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Operation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => 3,
            'sum' => 123131231,
            'user_id' => 2,
            'date' => $this->faker->dateTime,
            'comment' => 'a sd asdasda sdsadasda',
            'balance_snapshot' => 123131231,
        ];
    }
}
