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

        $app = [
            "status" => true,
            "data" => [
                "app_version" => "1.0",
                "app_link" => "#",
            ]
        ];

        \App\Models\User::factory()->create([
            'name' => 'Admin Panel',
            'user_id' => 1,
            'email' => 'admin@admin.admin',
            'role'  => '0',
            'password' => md5(md5(md5('123456'))),
            'game_settings' => json_encode($app)
        ]);


        $this->call([
            SiteSettingSeeder::class
        ]);
    }
}
