<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Number used for obfuscation (random number between 0 and 99999)
    |--------------------------------------------------------------------------
    */

    'obfuscator_prefix' => env('OBFUSCATOR_PREFIX', '43243'),

    /*
    |--------------------------------------------------------------------------
    | How often should a non-SSL url be checked for an SSL version
    | Don't check too often as it will slow down custom domains that
    | have no SSL activated.
    |--------------------------------------------------------------------------
    */

    'ssl_check_cache' => env('SSL_CHECK_CACHE', \Carbon\Carbon::now()->addDays(1)),

];
