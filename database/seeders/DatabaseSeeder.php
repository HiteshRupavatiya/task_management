<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name'  => 'hitesh',
            'email' => 'hitesh@gmail.com',
            'type'  => 'Super admin',
        ]);

        User::factory()->create([
            'name'  => 'sarman',
            'email' => 'sarman@gmail.com',
            'type'  => 'Admin',
        ]);

        User::factory()->create([
            'name'  => 'dharmik',
            'email' => 'dharmik@gmail.com',
            'type'  => 'Hr',
        ]);

        User::factory()->create([
            'name'  => 'gaurav',
            'email' => 'gaurav@gmail.com',
            'type'  => 'Team leader',
        ]);
    }
}
