<?php
// vendor/laravel/framework/src/Illuminate/Foundation/helpers.php

/**
 * Translate the given message.
 *
 * @param  string  $key
 * @param  array   $replace
 * @param  string  $locale
 * @return \Illuminate\Contracts\Translation\Translator|string|array|null
 */
function trans($key = null, $replace = [], $locale = null)
{
    if (is_null($key)) {
        return app('translator');
    }
  
    $override = str_replace('.', '-override.', $key);

    if (\Lang::has($override)) {
      return app('translator')->trans($override, $replace, $locale);
    } else {
      return app('translator')->trans($key, $replace, $locale);
    }
}