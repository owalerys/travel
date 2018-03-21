<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = \App\User::create([
            'name' => 'Admin Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'remember_token' => str_random(10),
        ]);

        $admin->assignRole('administrator');
        $admin->assignRole('editor');
        $admin->assignRole('author');
    }
}
