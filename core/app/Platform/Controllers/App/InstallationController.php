<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/*
|--------------------------------------------------------------------------
| Installation controller
|--------------------------------------------------------------------------
|
| Installation related logic
|
*/

class InstallationController extends \App\Http\Controllers\Controller {

  /**
   * Create user tables, prefix with `x_` to have them grouped
   */
  public static function createUserTables($user_id = null)
  {
    // Create user landing stats table if not exist
    $tbl_name = 'x_landing_stats_' . $user_id;

    if (! Schema::hasTable($tbl_name)) {
      Schema::create($tbl_name, function(Blueprint $table) {
        $table->bigIncrements('id');
        $table->bigInteger('landing_site_id')->unsigned();
        $table->foreign('landing_site_id')->references('id')->on('landing_sites')->onDelete('cascade');
        $table->bigInteger('landing_page_id')->unsigned();
        $table->foreign('landing_page_id')->references('id')->on('landing_pages')->onDelete('cascade');
        $table->char('fingerprint', 32)->nullable();
        $table->bigInteger('views')->unsigned()->default(1);
        $table->boolean('is_bot')->default(false);
        $table->string('ip', 40)->nullable();
        $table->string('language', 5)->nullable();
        $table->string('client_type', 32)->nullable();
        $table->string('client_name', 32)->nullable();
        $table->string('client_version', 32)->nullable();
        $table->string('os_name', 32)->nullable();
        $table->string('os_version', 32)->nullable();
        $table->string('os_platform', 32)->nullable();
        $table->string('device', 12)->nullable();
        $table->string('brand', 32)->nullable();
        $table->string('model', 32)->nullable();
        $table->string('bot_name', 32)->nullable();
        $table->string('bot_category', 32)->nullable();
        $table->string('bot_url', 200)->nullable();
        $table->string('bot_producer_name', 48)->nullable();
        $table->string('bot_producer_url', 128)->nullable();
        $table->decimal('lat', 10, 8)->nullable();
        $table->decimal('lng', 11, 8)->nullable();
        $table->json('meta')->nullable();
        $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
      });
    }

    // Create user form stats table if not exist
    $tbl_name = 'x_form_stats_' . $user_id;

    if (! Schema::hasTable($tbl_name)) {
      Schema::create($tbl_name, function(Blueprint $table) {
        $table->bigIncrements('id');
        $table->bigInteger('form_id')->unsigned();
        $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
        $table->bigInteger('landing_site_id')->unsigned()->nullable();
        $table->foreign('landing_site_id')->references('id')->on('landing_sites')->onDelete('SET NULL');
        $table->bigInteger('landing_page_id')->unsigned()->nullable();
        $table->foreign('landing_page_id')->references('id')->on('landing_pages')->onDelete('SET NULL');
        $table->char('fingerprint', 32)->nullable();
        $table->bigInteger('views')->unsigned()->default(1);
        $table->boolean('is_bot')->default(false);
        $table->string('ip', 40)->nullable();
        $table->string('language', 5)->nullable();
        $table->string('client_type', 32)->nullable();
        $table->string('client_name', 32)->nullable();
        $table->string('client_version', 32)->nullable();
        $table->string('os_name', 32)->nullable();
        $table->string('os_version', 32)->nullable();
        $table->string('os_platform', 32)->nullable();
        $table->string('device', 12)->nullable();
        $table->string('brand', 32)->nullable();
        $table->string('model', 32)->nullable();
        $table->string('bot_name', 32)->nullable();
        $table->string('bot_category', 32)->nullable();
        $table->string('bot_url', 200)->nullable();
        $table->string('bot_producer_name', 48)->nullable();
        $table->string('bot_producer_url', 128)->nullable();
        $table->decimal('lat', 10, 8)->nullable();
        $table->decimal('lng', 11, 8)->nullable();
        $table->json('meta')->nullable();
        $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
      });
    }

    // Create user form entries table if not exist
    $tbl_name = 'x_form_entries_' . $user_id;

    if (! Schema::hasTable($tbl_name)) {
      Schema::create($tbl_name, function(Blueprint $table) {
        $table->bigIncrements('id');
        $table->bigInteger('form_id')->unsigned();
        $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
        $table->bigInteger('landing_site_id')->unsigned()->nullable();
        $table->foreign('landing_site_id')->references('id')->on('landing_sites')->onDelete('SET NULL');
        $table->bigInteger('landing_page_id')->unsigned()->nullable();
        $table->foreign('landing_page_id')->references('id')->on('landing_pages')->onDelete('SET NULL');
        $table->boolean('confirmed')->default(false);
        $table->integer('clicks')->unsigned()->default(0);
        $table->integer('opens')->unsigned()->default(0);
        $table->integer('emails')->unsigned()->default(0);
        $table->tinyInteger('followups')->unsigned()->default(0);
        $table->dateTime('first_followup')->nullable();
        $table->dateTime('last_followup')->nullable();
        $table->dateTime('last_response')->nullable();
        $table->char('fingerprint', 32)->nullable();
        $table->string('ip', 40)->nullable();
        $table->string('language', 5)->nullable();
        $table->string('client_type', 32)->nullable();
        $table->string('client_name', 32)->nullable();
        $table->string('client_version', 32)->nullable();
        $table->string('os_name', 32)->nullable();
        $table->string('os_version', 32)->nullable();
        $table->string('os_platform', 32)->nullable();
        $table->string('device', 12)->nullable();
        $table->string('brand', 32)->nullable();
        $table->string('model', 32)->nullable();
        $table->decimal('lat', 10, 8)->nullable();
        $table->decimal('lng', 11, 8)->nullable();

        $table->string('email', 96);
        $table->string('personal_first_name', 250)->nullable();
        $table->string('personal_last_name', 250)->nullable();
        $table->string('personal_name', 250)->nullable();
        $table->tinyInteger('personal_gender')->unsigned()->nullable();
        $table->tinyInteger('personal_title')->unsigned()->nullable();
        $table->string('personal_impressum', 250)->nullable();
        $table->date('personal_birthday')->nullable();
        $table->string('personal_website', 250)->nullable();
        $table->string('personal_address1', 250)->nullable();
        $table->string('personal_address2', 250)->nullable();
        $table->string('personal_street', 250)->nullable();
        $table->string('personal_house_number', 15)->nullable();
        $table->string('personal_phone', 20)->nullable();
        $table->string('personal_mobile', 20)->nullable();
        $table->string('personal_fax', 20)->nullable();
        $table->string('personal_postal', 20)->nullable();
        $table->string('personal_city', 64)->nullable();
        $table->string('personal_state', 64)->nullable();
        $table->string('personal_country', 64)->nullable();
        $table->string('business_company', 64)->nullable();
        $table->string('business_job_title', 32)->nullable();
        $table->string('business_website', 250)->nullable();
        $table->string('business_email', 96)->nullable();
        $table->string('business_address1', 250)->nullable();
        $table->string('business_address2', 250)->nullable();
        $table->string('business_street', 250)->nullable();
        $table->string('business_house_number', 15)->nullable();
        $table->string('business_phone', 20)->nullable();
        $table->string('business_mobile', 20)->nullable();
        $table->string('business_fax', 20)->nullable();
        $table->string('business_postal', 20)->nullable();
        $table->string('business_city', 64)->nullable();
        $table->string('business_state', 64)->nullable();
        $table->string('business_country', 64)->nullable();
        $table->date('booking_date')->nullable();
        $table->date('booking_start_date')->nullable();
        $table->date('booking_end_date')->nullable();
        $table->time('booking_time')->nullable();
        $table->time('booking_start_time')->nullable();
        $table->time('booking_end_time')->nullable();
        $table->dateTime('booking_date_time')->nullable();
        $table->dateTime('booking_start_date_time')->nullable();
        $table->dateTime('booking_end_date_time')->nullable();

        $table->json('entry')->nullable();
        $table->json('meta')->nullable();
        $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
      });
    }

    // Create user email stats table if not exist
    $tbl_name = 'x_email_mailings_' . $user_id;

    if (! Schema::hasTable($tbl_name)) {
      Schema::create($tbl_name, function(Blueprint $table) {
        $table->bigIncrements('id');
        $table->bigInteger('email_campaign_id')->unsigned()->nullable();
        $table->foreign('email_campaign_id')->references('id')->on('email_campaigns')->onDelete('SET NULL');
        $table->bigInteger('email_id')->unsigned();
        $table->foreign('email_id')->references('id')->on('emails')->onDelete('cascade');
        $table->integer('recepients')->unsigned()->default(1);
        $table->integer('clicks')->unsigned()->default(0);
        $table->integer('opens')->unsigned()->default(0);
        $table->string('language', 5)->nullable();
        $table->json('meta')->nullable();
        $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
      });
    }
  }

  /**
   * Install database and seed
   */
  public static function migrate()
  {
    // Fix for "Specified key was too long error" error
    // https://laravel-news.com/laravel-5-4-key-too-long-error
    \Schema::defaultStringLength(191);

    \Artisan::call('migrate', [
        '--force' => true,
    ]);

    \Artisan::call('db:seed', [
        '--force' => true,
    ]);

    // Install modules
    \Artisan::call('module:migrate', [
        '--force' => true,
    ]);

    \Artisan::call('module:seed', [
        '--force' => true,
    ]);

    //\Artisan::call('key:generate');

    // If demo
    if (config('app.demo')) {
      \Artisan::call('db:seed', [
          '--force' => true,
          '--class' => 'DemoTableSeeder',
      ]);
    }
  }

  /**
   * Remove all tables
   */
  public static function clean()
  {
    /**
     * Empty all user directories
     */
    $gitignore = '*
!.gitignore';
    $dirs = [
      '/attachments/',
      '/core/storage/app/public/',
      '/core/storage/framework/cache/',
      '/core/storage/framework/sessions/',
      '/core/storage/framework/views/',
      '/core/storage/logs/',
      '/core/bootstrap/cache/',
      '/public/forms/',
      '/public/landingpages/',
      '/public/emails/',
      '/public/qr/',
      '/uploads/'
    ];

    foreach($dirs as $dir) {
      $full_dir = public_path() . $dir;
      if(\File::isDirectory($full_dir)) {

        $success = \File::deleteDirectory($full_dir, true);
        if($success) {
          // Deploy .gitignore
          \File::put($full_dir . '.gitignore', $gitignore);
        }
      }
    }

    /**
     * Drop all tables in database
     */
    $tables = [];
 
    \DB::statement('SET FOREIGN_KEY_CHECKS=0');
 
    foreach(\DB::select('SHOW TABLES') as $k => $v)
    {
      $tables[] = array_values((array)$v)[0];
    }
 
    foreach($tables as $table)
    {
      \Schema::drop($table);
    }
  }

  public function reset($key)
  {
    if($key == config('app.key'))
    {
      // Clean cache, database and files
      \Platform\Controllers\App\InstallationController::clean();

      // Migrate tables and demo data
      \Platform\Controllers\App\InstallationController::migrate();

      // Demo data
      if (config('app.demo')) {
/*
        // Create user tables
        \Platform\Controllers\App\InstallationController::createUserTables(1);

        // Create landing pages
        \Modules\LandingPages\Http\Controllers\FunctionsController::createPage('creso', 'Page A', 1);

        \Modules\LandingPages\Http\Controllers\FunctionsController::createPage('so-app', 'Page B', 1);

        $faker = \Faker\Factory::create();

        $startDate = '-2 months';
        $endDate = 'now';

        $lp_stats = 100;

        // Page 1
        $page = \Modules\LandingPages\Http\Models\Page::where('id', 1)->first();
        for ($i = 0; $i < $lp_stats; $i++) {
          \Modules\LandingPages\Http\Controllers\FunctionsController::addStat($page, $faker->userAgent(), $faker->ipv4(), $faker->dateTimeBetween($startDate, $endDate));
        }

        // Page 2
        $page = \Modules\LandingPages\Http\Models\Page::where('id', 2)->first();
        for ($i = 0; $i < $lp_stats; $i++) {
          \Modules\LandingPages\Http\Controllers\FunctionsController::addStat($page, $faker->userAgent(), $faker->ipv4(), $faker->dateTimeBetween($startDate, $endDate));
        }
*/
        // Demo seeds
        \Artisan::call('db:seed', [
            '--force' => true,
            '--class' => 'DemoTableSeeder',
        ]);
      }

      $demo_path = public_path() . '/../demo';
      if(\File::isDirectory($demo_path))
      {
        // Uploads
        $user_files_src = $demo_path . '/media/';
        $user_files_tgt = base_path() . '/../uploads/' . Core\Secure::staticHash(1) . '/';

        \File::copyDirectory($user_files_src, $user_files_tgt);
      }
    }
  }
}