<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Race;
use App\Models\Rider;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        Club::factory(10)
            ->create();

        for ($count = 0; $count < 20; $count++) {
            Race::factory()
                ->state(new Sequence(
                    fn () => ['club_id' => Club::all()->random()]
                ))
                ->create();
        }

        for ($count = 0; $count < 30; $count++) {

            Rider::factory()
                ->hasAttached(
                    Race::all()->random(),
                    [
                        'finish_position' => rand(1, 20),
                        'finish_time' => $faker->unixTime()
                    ]
                )
                ->create();
        }
    }
}
