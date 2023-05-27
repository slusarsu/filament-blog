<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Adm\Seeders\AdmPermissionSeeder;
use App\Adm\Seeders\AdmRoleSeeder;
use App\Adm\Seeders\AdmUserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
//         \App\Models\Post::factory(50)->create();
//
        app()->call(AdmPermissionSeeder::class);
        app()->call(AdmRoleSeeder::class);
        app()->call(AdmUserSeeder::class);
    }
}
