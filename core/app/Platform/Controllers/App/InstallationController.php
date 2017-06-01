<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;
use Illuminate\Http\Request;

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