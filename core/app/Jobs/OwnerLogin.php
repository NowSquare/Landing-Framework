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
      // Check for new module migrations
      \Artisan::call('module:migrate', [
          '--force' => true,
      ]);

      // Only seed modules once

      // Scenarios
      $records = \DB::table('scenario_if')->first();
      $records_exists = (empty($records)) ? false : true;

      if (! $records_exists) {
        \Artisan::call('module:seed', [
            'module' => 'Scenarios',
            '--force' => true,
        ]);
      }

    }
}
