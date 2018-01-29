<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AirlineDataParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'airline:summarize {json : the input json file} {csv : the output csv file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $jsonFh = fopen($this->argument('json'), 'r');

        $decodedJson = json_decode(fread($jsonFh, 2000000), true);

        fclose($jsonFh);

        $csvFh = fopen($this->argument('csv'), 'w');

        fputcsv($csvFh, [
            'State',
            'Air Carrier',
            'Flight Type',
            'Stage Type',
            'Year',
            'Months',
            'Passengers Carried',
            'Passenger-KM performed (1000s)',
            'Seat-KM available (1000s)',
            'Passenger load factor'
        ]);

        foreach ($decodedJson as $dumpKey => $dumpResult) {
            foreach ($dumpResult as $responseParam => $paramData) {
                if ($responseParam === 'data') {
                    foreach ($paramData as $datumKey => $datum) {
                        fputcsv($csvFh, $datum);
                    }
                }
            }
        }

        fclose($csvFh);
    }
}
