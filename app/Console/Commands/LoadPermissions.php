<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class LoadPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Customized command to load initial system permissions';

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
    public function handle(): void
    {
        $this->warn('Running Permission Seeder');

        Artisan::call('db:seed', [
            '--class' => \PermissionSeeder::class,
            '--force' => true
        ]);

        $this->info('Seeding Complete');
    }
}
