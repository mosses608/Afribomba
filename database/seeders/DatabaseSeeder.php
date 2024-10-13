<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(ContainerSeeder::class);

        User::create([
            'staff_id' => 'ST 141',
            'staff_name' => 'Mosses',
            'staff_role' => 'Admin',
            'staff_email' => 'mosses608@gmail.com',
            'staff_phone' => '0768272954',
            'username' => 'mosses@123',
            'password' => '123456789',
        ]);  
    }
}
