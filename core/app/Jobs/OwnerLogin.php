<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class OwnerLogin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      /**
       * Check for new module migrations
       */

      // Beacons
      if (! \Schema::hasTable('beacons')) {
        // Migrate
        \Artisan::call('module:migrate', [
            'module' => 'Beacons',
            '--force' => true,
        ]);

        // Seed
        \Artisan::call('module:seed', [
            'module' => 'Beacons',
            '--force' => true,
        ]);
      }

      // Geofences
      if (! \Schema::hasTable('geofences')) {
        // Migrate
        \Artisan::call('module:migrate', [
            'module' => 'Geofences',
            '--force' => true,
        ]);

        // Seed
        \Artisan::call('module:seed', [
            'module' => 'Geofences',
            '--force' => true,
        ]);
      }

      // Scenarios
      if (! \Schema::hasTable('scenarios')) {
        // Migrate
        \Artisan::call('module:migrate', [
            'module' => 'Scenarios',
            '--force' => true,
        ]);

        // Seed
        \Artisan::call('module:seed', [
            'module' => 'Scenarios',
            '--force' => true,
        ]);
      }

    }
}
