<?php

namespace Database\Factories;

use App\Models\Rider;
use Illuminate\Database\Eloquent\Factories\Factory;

class RiderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rider::class;
    private $genders = ['Male', 'Female', 'Other'];
    private $gradings = [
        'Master',
        'Advanced',
        'Intermediate',
        'Beginner'
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'firstname' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'grading' => $this->faker->randomElement($this->gradings),
            'age' => $this->faker->numberBetween(10, 99),
            'gender' => $this->faker->randomElement($this->genders)
        ];
    }
}
