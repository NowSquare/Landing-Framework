<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('resellers')->insert([
            'api_token' => str_random(60),
            'name' => 'Landing Framework',
            'domain' => '*',
            'active' => true,
            'logo' => '/assets/branding/icon-light.svg',
            'logo_square' => '/assets/branding/square.svg',
            'favicon' => '/assets/branding/favicon.ico',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('plans')->insert([
            'order' => 1,
            'reseller_id' => 1,
            'name' => 'full_access',
            'price1' => 0,
            'price1_string' => '0',
            'active' => false,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('users')->insert([
            'is_reseller_id' => 1,
            'reseller_id' => 1,
            'plan_id' => 1,
            'name' => 'System Owner',
            'email' => 'info@example.com',
            'password' => bcrypt('welcome'),
            'api_token' => str_random(60),
            'confirmed' => 1,
            'role' => 'owner',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Categories
        $order = 10;

        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'bars_restaurants',
            'icon' => 'restaurant',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'art_design',
            'icon' => 'camera',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'shows_events',
            'icon' => 'headset',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'fashion_clothing',
            'icon' => 'shirt',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'household_interior',
            'icon' => 'home',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'health_fitness',
            'icon' => 'heart',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'beauty_esthetics',
            'icon' => 'rose',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'electronics_communication',
            'icon' => 'wifi',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'toys_games',
            'icon' => 'game-controller-b',
            'active' => true
        ]);

        $order += 10;
        DB::table('categories')->insert([
            'order' => $order,
            'reseller_id' => 1,
            'name' => 'travel_tourism',
            'icon' => 'pin',
            'active' => true
        ]);

    }
}
