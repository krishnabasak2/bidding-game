<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin Panel',
            'user_id' => 1,
            'email' => 'admin@admin.admin',
            'role'  => '0',
            'password' => md5(md5(md5('123456')))
        ]);


        $this->call([
            SiteSettingSeeder::class
        ]);
    }
}
