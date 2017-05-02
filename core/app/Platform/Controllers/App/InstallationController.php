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
    \Artisan::call('migrate', [
        '--force' => true,
    ]);

    \Artisan::call('db:seed', [
        '--force' => true,
    ]);

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