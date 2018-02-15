<?php

namespace App\Console\Commands;

use App\Content\Category;
use App\Repositories\ContentRepository;
use App\Repositories\SchemaRepository;
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

    protected $schemaRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SchemaRepository $schemaRepository)
    {
        parent::__construct();

        $this->schemaRepository = $schemaRepository;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->warn('Parsing content');

        $this->schemaRepository->parseContent();

        $this->info('Content parsed successfully');

        $count = 0;

        foreach ($this->schemaRepository->getVersionsArray() as $version) {
            foreach ($this->schemaRepository->getSlugsArray($version) as $category) {
                $this->info('v' . $version . ':' . $category);
                $count++;
            }
        }

        if ($count > 1) {
            $this->info('Found ' . $count . ' categories');
        } elseif ($count === 1) {
            $this->info('Found 1 category');
        } else {
            $this->warn('No categories loaded in schema.');
        }

    }
}
