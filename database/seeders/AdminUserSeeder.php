<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'job_admin@yopmail.com',
            'password' => bcrypt('admin@1234'),
            'email_verified_at' => Carbon::now(),
            'type' => 1
        ]);
    }
}
