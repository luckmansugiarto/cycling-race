<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\Race;
use Illuminate\Database\Eloquent\Factories\Factory;

class RaceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Race::class;
    private $statuses = ['PENDING', 'COMPLETED'];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $raceTitleCounter = 1;
        return [
            'title' => 'Race ' . ($raceTitleCounter++),
            'club_id' => Club::factory(),
            'start_date' => $this->faker->dateTimeBetween('-60 days', '-30 days'),
            'end_date' => $this->faker->dateTimeBetween('now', '+60 days'),
            'address' => $this->faker->address,
            'status' => $this->faker->randomElement($this->statuses)
        ];
    }
}
