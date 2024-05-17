<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Testing
    |--------------------------------------------------------------------------
    |
    | The values described here are used to locate Inertia components on the
    | filesystem. For instance, when using `assertInertia`, the assertion
    | attempts to locate the component as a file relative to any of the
    | paths AND with any of the extensions specified here.
    |
    */

    'ssr' => [

        'enabled' => env('APP_ENV') === 'production',

        'url' => 'http://127.0.0.1:13714/render',

    ],

    'testing' => [

        'ensure_pages_exist' => true,

        'page_paths' => [

            resource_path('js/Pages'),

        ],

        'page_extensions' => [

            'js',
            'jsx',
            'svelte',
            'ts',
            'vue',

        ],

    ],

];
