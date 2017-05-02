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
        $system_user_count = 25;

        // Beacons
        $beacon_count = 15;

        // Geofences
        $geofence_count = 15;

        // Scenarios
        $beacon_scenario_monitor_count = 15;
        $beacon_scenario_range_count = 15;
        $geofence_scenario_monitor_count = 15;

        // Analytics
        $startDate = '-2 months';
        $endDate = 'now';
        $analytics_unique_user_count = 40;
        $analytics_card_view_count = 300;
        $analytics_beacon_monitor_count = 250;
        $analytics_beacon_range_count = 250;
        $analytics_geofence_monitor_count = 250;

        // Cards
        $cards = [];
        $cards[] = ["name" => "McDonald's", "description" => "There's always something brewing in our kitchens. Get in the loop with the latest on promotions, events, new products, specials, and more."];
        $cards[] = ["name" => "Disney Store", "description" => "The Disney Store offers a one-of-a-kind, interactive experience for Disney fans of all ages."];
        $cards[] = ["name" => "Apple Store", "description" => "Experience the wide world of Apple at the Apple Store. Shop for Apple computers, compare iPod and iPhone models, and discover Apple and third-party accessories, software, and much more."];
        $cards[] = ["name" => "ECCO Shoes", "description" => "ECCO, a world-leading brand of shoes combining style and comfort, has built its success on great design, leather quality, and innovative technology."];
        $cards[] = ["name" => "Foot Locker", "description" => "Foot Locker is a leading global athletic footwear and apparel retailer. Its stores offer the latest in athletic-inspired performance products, manufactured primarily by the leading athletic brands."];
        $cards[] = ["name" => "Hallmark", "description" => "Exclusive card and gift products and exceptional service in a friendly and pleasant environment. "];
        $cards[] = ["name" => "Jack & Jones", "description" => "Jack & Jones is a jeans brand that makes it easy for fashion conscious men to create their own personal style. In terms of design, the four lines, Jeans Intelligence, Premium, Vintage and Premium Tech, have different target groups and expressions, but every piece of clothing can be combined with our jeans."];
        $cards[] = ["name" => "KFC/TACO BELL", "description" => "KFC is the largest chain of chicken restaurants in the world. The first restaurant opened in Calgary in 1954. Today over 2.5 billion great tasting meals are served every year in over 10,000 KFC Restaurants worldwide."];
        $cards[] = ["name" => "Michael Kors", "description" => "Michael Kors is recognized as a preeminent designer for luxury accessories and ready to wear. His namesake company, established in 1981, currently produces a range of products through his Michael Kors Collection."];
        $cards[] = ["name" => "Banana Republic", "description" => "Banana Republic is a global accessible luxury brand that delivers the best in city style. Characterized by elevated design and luxurious fabrications, the Banana Republic lifestyle collections include men's and women's apparel, handbags, jewelery and fragrance."];
        $cards[] = ["name" => "The Body Shop", "description" => "We believe there is only one way to beautiful, nature's way. We've believed this for years and still do. We constantly seek out wonderful natural ingredients from all four corners of the globe, and we bring you products bursting with effectiveness to enhance your natural beauty and express your unique personality."];
        $cards[] = ["name" => "The Gap", "description" => "Since 1969, Gap has provided customers with clothing and accessories that enhance personal style while providing great value and service."];
        $cards[] = ["name" => "LUSH Cosmetics", "description" => "LUSH Fresh Handmade Natural Cosmetics complete range of natural handmade bath and body products including handmade natural soaps, bath bombs and MORE!"];
        $cards[] = ["name" => "Pandora", "description" => "PANDORA designs, manufactures and markets hand-finished and modern jewellery made from genuine materials at affordable prices."];
        $cards[] = ["name" => "Sears Optical", "description" => "At Sears Optical, we value you, our customer, and are committed to helping you find the best solutions for your eyewear needs."];
        $cards[] = ["name" => "Starbucks Coffee", "description" => "Our coffeehouses have become a beacon for coffee lovers everywhere. Why do they insist on Starbucks? Because they know they can count on genuine service, an inviting atmosphere and a superb cup of expertly roasted and richly brewed coffee every time."];
        $cards[] = ["name" => "Subway", "description" => "Subway, the way a sandwich should be. We sell fresh, hot and cold submarines sandwiches with choice of Italian, wheat, hearty Italian, harvest wheat and parmesan oregano breads baked daily."];
        $cards[] = ["name" => "TOYS 'R' US", "description" => "Find the largest selection of kids toys, games, and electronics for children of all ages!"];
        $cards[] = ["name" => "Zara", "description" => "Zara is one of the largest international fashion companies. The customer is at the heart of our unique business model, which includes design, production, distribution and sales through our extensive retail network."];

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

        DB::table('beacon_uuids')->insert([
            'user_id' => 1,
            'uuid' => 'fda50693-a4e2-4fb1-afcf-c6eb07647825'
        ]);

        // Beacons
        for ($i = 1; $i < $beacon_count + 1; $i++) {
          $date = $faker->dateTimeThisMonth($max = 'now');
          $lat = $faker->latitude($min = $lat_min, $max = $lat_max);
          $lng = $faker->longitude($min = $lng_min, $max = $lng_max);

          DB::table('beacons')->insert([
              'user_id' => 1,
              'name' => 'Beacon ' . $i,
              'uuid' => 'fda50693-a4e2-4fb1-afcf-c6eb07647825',
              'major' => 10004,
              'minor' => 54480,
              'active' => 1,
              'zoom' => 15,
              'lat' => $lat,
              'lng' => $lng,
              'location' => \DB::raw('POINT(' . $lat . ',' . $lng . ')'),
              'created_at' => $date,
              'updated_at' => $date
          ]);
        }

        // Geofences
        for ($i = 1; $i < $geofence_count + 1; $i++) {
          $date = $faker->dateTimeThisMonth($max = 'now');
          $lat = $faker->latitude($min = $lat_min, $max = $lat_max);
          $lng = $faker->longitude($min = $lng_min, $max = $lng_max);

          DB::table('geofences')->insert([
              'user_id' => 1,
              'name' => 'Geofence ' . $i,
              'active' => 1,
              'zoom' => 16,
              'lat' => $lat,
              'lng' => $lng,
              'radius' => ceil(mt_rand(100, 300) / 10) * 10,
              'location' => \DB::raw('POINT(' . $lat . ',' . $lng . ')'),
              'created_at' => $date,
              'updated_at' => $date
          ]);
        }

        DB::table('campaign_apps')->insert([
            'user_id' => 1,
            'name' => 'Production App',
            'api_token' => 'MO8KeFqpyAjqSfJkpQ9bxzKrN0HGzVXsGqIaPI23MkE1b37opDhg0yGjyJVE',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('campaign_apps')->insert([
            'user_id' => 1,
            'name' => 'Test App',
            'api_token' => 'FlNQDnEVXE4DYUB4F2X2TTBz9BApOh0iE9o6q0yUcnPPI8S43gjJdUyZlzw3',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('campaigns')->insert([
            'user_id' => 1,
            'name' => 'Food',
            'language' => 'en',
            'timezone' => 'Europe/Amsterdam',
            'active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('campaigns')->insert([
            'user_id' => 1,
            'name' => 'Retail',
            'language' => 'en',
            'timezone' => 'Europe/Amsterdam',
            'active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('campaigns')->insert([
            'user_id' => 1,
            'name' => 'Expo',
            'language' => 'en',
            'timezone' => 'Europe/Amsterdam',
            'active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('campaigns')->insert([
            'user_id' => 1,
            'name' => 'Promos',
            'language' => 'en',
            'timezone' => 'Europe/Amsterdam',
            'active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('app_campaigns')->insert([
            'app_id' => 1,
            'campaign_id' => 1
        ]);

        DB::table('app_campaigns')->insert([
            'app_id' => 2,
            'campaign_id' => 2
        ]);

        DB::table('app_campaigns')->insert([
            'app_id' => 1,
            'campaign_id' => 3
        ]);

        DB::table('app_campaigns')->insert([
            'app_id' => 1,
            'campaign_id' => 4
        ]);

        // Beacon scenarios enter/exit
        $scenario_count1 = $beacon_scenario_monitor_count;
        for ($scenario_id = 1; $scenario_id < $scenario_count1 + 1; $scenario_id++) {
          DB::table('scenarios')->insert([
              'campaign_id' => mt_rand(1, 4),
              'scenario_if_id' => mt_rand(1, 2),
              'scenario_then_id' => 4,
              'scenario_day_id' => 1,
              'scenario_time_id' => 1,
              'notification' => 'Welcome beacon scenario ' . $scenario_id . '!',
              'active' => 1,
              'open_url' => 'https://madewithpepper.com?scenario=' . $scenario_id,
              'created_at' => date('Y-m-d H:i:s'),
              'updated_at' => date('Y-m-d H:i:s')
          ]);

          DB::table('beacon_scenario')->insert([
              'beacon_id' => mt_rand(1, $beacon_count),
              'scenario_id' => $scenario_id
          ]);
        }

        // Beacon scenarios range
        $scenario_count2 = $beacon_scenario_range_count;
        for ($scenario_id = $scenario_count1 + 1; $scenario_id < ($scenario_count1 + $scenario_count2) + 1; $scenario_id++) {
          DB::table('scenarios')->insert([
              'campaign_id' => mt_rand(1, 4),
              'scenario_if_id' => mt_rand(3, 5),
              'scenario_then_id' => 4,
              'scenario_day_id' => 1,
              'scenario_time_id' => 1,
              'active' => 1,
              'open_url' => 'https://madewithpepper.com?scenario=' . $scenario_id,
              'created_at' => date('Y-m-d H:i:s'),
              'updated_at' => date('Y-m-d H:i:s')
          ]);

          DB::table('beacon_scenario')->insert([
              'beacon_id' => mt_rand(1, $beacon_count),
              'scenario_id' => $scenario_id
          ]);
        }

        // Geofence scenarios enter/exit
        $scenario_count3 = $geofence_scenario_monitor_count;
        for ($scenario_id = ($scenario_count1 + $scenario_count2) + 1; $scenario_id < ($scenario_count1 + $scenario_count2 + $scenario_count3) + 1; $scenario_id++) {
          DB::table('scenarios')->insert([
              'campaign_id' => mt_rand(1, 4),
              'scenario_if_id' => mt_rand(1, 2),
              'scenario_then_id' => 4,
              'scenario_day_id' => 1,
              'scenario_time_id' => 1,
              'notification' => 'Welcome geofence scenario ' . $scenario_id . '!',
              'active' => 1,
              'open_url' => 'https://madewithpepper.com?scenario=' . $scenario_id,
              'created_at' => date('Y-m-d H:i:s'),
              'updated_at' => date('Y-m-d H:i:s')
          ]);

          DB::table('geofence_scenario')->insert([
              'geofence_id' => mt_rand(1, $geofence_count),
              'scenario_id' => $scenario_id
          ]);
        }

        // Cards
        $card_id = 1;

        foreach ($cards as $card) {
          $date = $faker->dateTimeBetween($startDate, $endDate);
          $lat = $faker->latitude($min = $lat_min, $max = $lat_max);
          $lng = $faker->longitude($min = $lng_min, $max = $lng_max);

          DB::table('cards')->insert([
              'user_id' => 1,
              'name' => $card['name'],
              'description' => $card['description'],
              'language' => 'en',
              'timezone' => 'Europe/Amsterdam',
              'active' => 1,
              'zoom' => 15,
              'lat' => $lat,
              'lng' => $lng,
              'location' => \DB::raw('POINT(' . $lat . ',' . $lng . ')'),
              'created_at' => $date,
              'updated_at' => $date
          ]);

          // Add card to all categories
          for ($i = 1; $i < 11; $i++) {
            DB::table('category_card')->insert(['category_id' => $i, 'card_id' => $card_id]);
          }

          // Add card to two random campaigns
          DB::table('campaign_card')->insert(['campaign_id' => mt_rand(1, 2), 'card_id' => $card_id]);
          DB::table('campaign_card')->insert(['campaign_id' => mt_rand(3, 4), 'card_id' => $card_id]);

          $card_id++;
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

        // Card analytics
        $limit = $analytics_card_view_count;

        for ($i = 0; $i < $limit; $i++) {
          $date = $faker->dateTimeBetween($startDate, $endDate);
          $lat = $faker->latitude($min = $lat_min, $max = $lat_max);
          $lng = $faker->longitude($min = $lng_min, $max = $lng_max);
          $user_id = mt_rand(0, count($user)-1);
          $card_id = mt_rand(1, count($cards));

          $card_stat = new \Platform\Models\Analytics\CardStat;

          $card_stat->user_id = 1;
          $card_stat->card_id = $card_id;
          $card_stat->ip = $user[$user_id]['ip'];
          $card_stat->device_uuid = $user[$user_id]['uuid'];
          $card_stat->platform = $user[$user_id]['platform'];
          $card_stat->model = $user[$user_id]['model'];
          $card_stat->lat = $lat;
          $card_stat->lng = $lng;
          $card_stat->created_at = $date;

          $card_stat->save();

          // Increment card view
          DB::table('cards')->whereId($card_id)->increment('views');
        }

        // Beacons enter/exit analytics (interactions)
        $limit = $analytics_beacon_monitor_count;

        for ($i = 0; $i < $limit; $i++) {
          $date = $faker->dateTimeBetween($startDate, $endDate);
          $lat = $faker->latitude($min = $lat_min, $max = $lat_max);
          $lng = $faker->longitude($min = $lng_min, $max = $lng_max);
          $user_id = mt_rand(0, count($user)-1);
          $beacon_id = mt_rand(1, $beacon_count);
          $scenario_id = mt_rand(1, $scenario_count1);
          $state = ['enter', 'exit'];

          $interaction = new \Platform\Models\Location\Interaction;

          $interaction->user_id = 1;
          $interaction->campaign_id = mt_rand(1, 4);
          $interaction->scenario_id = $scenario_id;
          $interaction->beacon_id = $beacon_id;
          $interaction->beacon = 'Beacon ' . $beacon_id;
          $interaction->state = $state[mt_rand(0, count($state) - 1)];
          $interaction->ip = $user[$user_id]['ip'];
          $interaction->device_uuid = $user[$user_id]['uuid'];
          $interaction->platform = $user[$user_id]['platform'];
          $interaction->model = $user[$user_id]['model'];
          $interaction->lat = $lat;
          $interaction->lng = $lng;
          $interaction->created_at = $date;

          $interaction->save();

          // Increment trigger
          DB::table('scenarios')->whereId($scenario_id)->increment('triggers');
          DB::table('beacons')->whereId($beacon_id)->increment('triggers');
        }

        // Beacons range analytics (interactions)
        $limit = $analytics_beacon_range_count;

        for ($i = 0; $i < $limit; $i++) {
          $date = $faker->dateTimeBetween($startDate, $endDate);
          $lat = $faker->latitude($min = $lat_min, $max = $lat_max);
          $lng = $faker->longitude($min = $lng_min, $max = $lng_max);
          $user_id = mt_rand(0, count($user)-1);
          $beacon_id = mt_rand(1, $beacon_count);
          $scenario_id = mt_rand($scenario_count1 + 1, ($scenario_count1 + $scenario_count2));
          $state = ['immediate', 'near', 'far'];

          $interaction = new \Platform\Models\Location\Interaction;

          $interaction->user_id = 1;
          $interaction->campaign_id = mt_rand(1, 4);
          $interaction->scenario_id = $scenario_id;
          $interaction->beacon_id = $beacon_id;
          $interaction->beacon = 'Beacon ' . $beacon_id;
          $interaction->state = $state[mt_rand(0, count($state) - 1)];
          $interaction->ip = $user[$user_id]['ip'];
          $interaction->device_uuid = $user[$user_id]['uuid'];
          $interaction->platform = $user[$user_id]['platform'];
          $interaction->model = $user[$user_id]['model'];
          $interaction->lat = $lat;
          $interaction->lng = $lng;
          $interaction->created_at = $date;

          $interaction->save();

          // Increment triggers
          DB::table('scenarios')->whereId($scenario_id)->increment('triggers');
          DB::table('beacons')->whereId($beacon_id)->increment('triggers');
        }

        // Geofence enter/exit analytics (interactions)
        $limit = $analytics_geofence_monitor_count;

        for ($i = 0; $i < $limit; $i++) {
          $date = $faker->dateTimeBetween($startDate, $endDate);
          $lat = $faker->latitude($min = $lat_min, $max = $lat_max);
          $lng = $faker->longitude($min = $lng_min, $max = $lng_max);
          $user_id = mt_rand(0, count($user)-1);
          $geofence_id = mt_rand(1, $geofence_count);
          $scenario_id = mt_rand(($scenario_count1 + $scenario_count2) + 1, ($scenario_count1 + $scenario_count2 + $scenario_count3));
          $state = ['enter', 'exit'];

          $interaction = new \Platform\Models\Location\Interaction;

          $interaction->user_id = 1;
          $interaction->campaign_id = mt_rand(1, 4);
          $interaction->scenario_id = $scenario_id;
          $interaction->geofence_id = $geofence_id;
          $interaction->geofence = 'Geofence ' . $geofence_id;
          $interaction->state = $state[mt_rand(0, count($state) - 1)];
          $interaction->ip = $user[$user_id]['ip'];
          $interaction->device_uuid = $user[$user_id]['uuid'];
          $interaction->platform = $user[$user_id]['platform'];
          $interaction->model = $user[$user_id]['model'];
          $interaction->lat = $lat;
          $interaction->lng = $lng;
          $interaction->created_at = $date;

          $interaction->save();

          // Increment triggers
          DB::table('scenarios')->whereId($scenario_id)->increment('triggers');
          DB::table('geofences')->whereId($geofence_id)->increment('triggers');
        }
    }
}