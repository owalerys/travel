<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ApplicationFresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop and rebuild all database tables, reseed, and install default permissions';

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
        $this->warn('Dropping all tables and running migrations from scratch');
        Artisan::call('migrate:fresh');
        $this->info('Migrations complete');

        $this->warn('Running table seeding');
        Artisan::call('db:seed');
        $this->info('Table seeding complete');
    }
}
