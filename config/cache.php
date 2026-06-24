<?php

use Illuminate\Support\Str;

return [

    

    'default' => env('CACHE_STORE', 'database'),

  

    'prefix' => env('CACHE_PREFIX', Str::slug((string) env('APP_NAME', 'laravel')).'-cache-'),

    

    'serializable_classes' => false,

];
