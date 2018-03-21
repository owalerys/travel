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
        $data = [
            ['iata_code' => 'AA', 'icao_code' => 'AAA', 'name' => 'American Airlines', 'country_code' => 'US'],
            ['iata_code' => 'DL', 'icao_code' => 'DLL', 'name' => 'Delta Airlines', 'country_code' => 'US'],
            ['iata_code' => 'AC', 'icao_code' => 'CAA', 'name' => 'Air Canada', 'country_code' => 'CA'],
            ['iata_code' => 'UA', 'icao_code' => 'UAA', 'name' => 'United Airlines', 'country_code' => 'US'],
            ['iata_code' => 'B9', 'icao_code' => 'B99', 'name' => 'JetBlue Airways', 'country_code' => 'US'],
            ['iata_code' => 'F9', 'icao_code' => 'F99', 'name' => 'Frontier Airlines', 'country_code' => 'US'],
            ['iata_code' => 'NK', 'icao_code' => 'NKK', 'name' => 'Spirit Airlines', 'country_code' => 'US'],
            ['iata_code' => 'SW', 'icao_code' => 'SWW', 'name' => 'Southwest Airlines', 'country_code' => 'US'],
            ['iata_code' => 'LO', 'icao_code' => 'LOT', 'name' => 'LOT Polish Airlines', 'country_code' => 'PL'],
            ['iata_code' => 'LH', 'icao_code' => 'LHX', 'name' => 'Lufthansa', 'country_code' => 'DE'],
        ];

        foreach ($data as $datum) {
            App\Airline::create($datum);
        }
    }
}
