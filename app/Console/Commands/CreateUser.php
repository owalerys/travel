<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {email} {name} {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user';

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
        $password = str_random(12);

        $this->info($password);

        $user = User::create([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => Hash::make($password)
        ]);

        if ($this->argument('type') === 'author') {
            $user->assignRole('editor');
            $user->assignRole('author');
        } elseif ($this->argument('type') === 'admin') {
            $user->assignRole('administrator');
            $user->assignRole('editor');
            $user->assignRole('author');
        }
    }
}
