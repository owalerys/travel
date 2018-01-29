<?php

namespace App\Console\Commands;

use App\Content\Category;
use Illuminate\Console\Command;

class ParseContentConfiguration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the content configuration by attempting a parsing of it';

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
     * @return mixed
     */
    public function handle()
    {
        return new Category\Collection(config('content.categories'));
    }
}
