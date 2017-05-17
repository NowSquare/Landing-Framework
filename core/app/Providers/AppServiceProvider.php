<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      // Fix for "Specified key was too long error" error
      // https://laravel-news.com/laravel-5-4-key-too-long-error
      \Schema::defaultStringLength(191);

      $url_current = str_replace('https://', '', str_replace('http://', '', url()->current()));
      $reset_url = request()->server('HTTP_HOST') . '/reset/' . config('app.key');

      if ($url_current != $reset_url) {
        // Check if database table exists
        if (! \Schema::hasTable('users')) {
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
      }

      // Override reseller settings
      $reseller = \Platform\Controllers\Core\Reseller::get();

      // Make $reseller accessible in all views
      view()->share('reseller', $reseller);
      //view()->share('ip_address', \Platform\Controllers\Helper\Client::ip());

      if (isset($reseller->mail_from_address)) {
        if ($reseller->mail_from_address != '') config(['mail.from.address' => $reseller->mail_from_address]);
        if ($reseller->mail_from_name != '') config(['mail.from.name' => $reseller->mail_from_name]);
        if ($reseller->mail_driver != '') config(['mail.driver' => $reseller->mail_driver]);
        if ($reseller->mail_mailgun_domain != '') config(['services.mailgun.domain' => $reseller->mail_mailgun_domain]);
        if ($reseller->mail_mailgun_secret != '') config(['services.mailgun.secret' => $reseller->mail_mailgun_secret]);
        if ($reseller->mail_host != '') config(['mail.host' => $reseller->mail_host]);
        if ($reseller->mail_port != '') config(['mail.port' => $reseller->mail_port]);
        if ($reseller->mail_encryption != '') config(['mail.encryption' => $reseller->mail_encryption]);
        if ($reseller->mail_username != '') config(['mail.username' => $reseller->mail_username]);
        if ($reseller->mail_password != '') config(['mail.password' => $reseller->mail_password]);
        if ($reseller->avangate_key != '') config(['avangate.key' => $reseller->avangate_key]);
      }

      // Namespaces
      // Landing pages
      view()->addNamespace('template.landingpages', base_path('../templates/landingpages/'));
      view()->addNamespace('block.landingpages', base_path('../blocks/landingpages/'));
      view()->addNamespace('public.landingpages', base_path('../public/landingpages/site/'));

      // Forms
      view()->addNamespace('template.forms', base_path('../templates/forms/'));
      view()->addNamespace('block.forms', base_path('../blocks/forms/'));
      view()->addNamespace('public.forms', base_path('../public/forms/form/'));

      // Collection::mapWithKeys() works incorrectly for keys with integer values
      // https://github.com/laravel/framework/issues/15409
      collect()->macro('mapWithKeys_v2', function ($callback) {

        $result = [];

        foreach ($this->items as $key => $value) {
          $assoc = $callback($value, $key);

          foreach ($assoc as $mapKey => $mapValue) {
            $result[$mapKey] = $mapValue;
          }
        }

        return new static($result);
      });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
