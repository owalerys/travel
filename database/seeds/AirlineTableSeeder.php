<?php

use Illuminate\Database\Seeder;

class AirlineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Airline::class, 10)->create();
    }
}
