<?php

use Illuminate\Database\Seeder;

class DemoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=DemoTableSeeder
     *
     * @return void
     */
    public function run()
    {
        /*
         |--------------------------------------------------------------------------
         | Demo data settings
         |--------------------------------------------------------------------------
         */

        // Bounding box for random generated locations
        $lat_min = 51.439747;
        $lng_min = 5.477696;
        $lat_max = 51.438810;
        $lng_max = 5.479960;

        // System users
        $system_user_count = 0;

        // Analytics
        $startDate = '-2 months';
        $endDate = 'now';
        $analytics_unique_user_count = 40;

        // Landing pages
        $lp_stats = 100;

        /*
         |--------------------------------------------------------------------------
         | Generate demo data
         |--------------------------------------------------------------------------
         */

        $faker = Faker\Factory::create();

        for ($i = 0; $i < $system_user_count; $i++) {
            DB::table('users')->insert([
                'reseller_id' => 1,
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'password' => bcrypt('welcome'),
                'confirmed' => 1,
                'role' => 'user',
                'logins' => $faker->numberBetween($min = 1, $max = 50),
                'last_ip' => $faker->ipv4(),
                'last_login' => $faker->dateTimeThisMonth($max = 'now'),
                'created_at' => $faker->dateTimeThisYear($max = '-1 months')
            ]);
        }

        // Analytics users
        $user = [];
        for ($i = 0; $i < $analytics_unique_user_count; $i++) {
          $user[] = [
            'ip' => $faker->ipv4(), 
            'ua' => $faker->userAgent()
          ];
        }

        // Landing page stats
/*
        // Page 1
        $page = \Modules\LandingPages\Http\Models\Page::where('id', 1)->first();
        for ($i = 0; $i < $lp_stats; $i++) {
          \Modules\LandingPages\Http\Controllers\FunctionsController::addStat($page, $faker->userAgent());
        }

        // Page 2
        $page = \Modules\LandingPages\Http\Models\Page::where('id', 2)->first();
        for ($i = 0; $i < $lp_stats; $i++) {
          \Modules\LandingPages\Http\Controllers\FunctionsController::addStat($page, $faker->userAgent());
        }*/
    }
}