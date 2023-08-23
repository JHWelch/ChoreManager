<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Demo Mode Enabled
    |--------------------------------------------------------------------------
    |
    | This mode is used to demo the app to prospective users. When demo mode
    | is enabled, the application will reseed the database nightly, not enabled
    | authentication, and display an informational banner.
    |
    */

    'enabled' => env('DEMO_MODE', false),
    'repository_url' => env('DEMO_REPO_URL', 'https://github.com/JHWelch/ChoreManager'),
];
