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
        $system_user_count = 15;

        // Analytics
        $startDate = '-2 months';
        $endDate = 'now';
        $analytics_unique_user_count = 40;

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
          $_platform = ['Android', 'iOS'];
          $platform = $_platform[mt_rand(0, count($_platform) - 1)];

          if ($platform == 'iOS') {
            $_model = ['iPad4,1', 'iPad5,4', 'iPhone8,2', 'iPhone8,1', 'iPhone7,2', 'iPhone9,2', 'iPhone9,1'];
          } else {
            $_model = ['Pixel', 'Pixel XL', 'LG G5', 'HTC 10', 'HTC Bolt', 'LG V20', 'Samsung Galaxy S7 Edge', 'Sony Xperia XZ', 'Moto X'];
          }
          $model = $_model[mt_rand(0, count($_model) - 1)];

          $user[] = [
            'ip' => $faker->ipv4(), 
            'uuid' => $faker->uuid(), 
            'platform' => $platform, 
            'model' => $model
          ];
        }
    }
}