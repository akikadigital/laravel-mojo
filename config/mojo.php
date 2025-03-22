<?php

// config for Akika/Mojo
return [
    'environment' => env('MOJO_DEV_ENVIRONEMT', 'dev'),

    'dev' => [
        'cross_switch_url' => env('MOJO_DEV_CROSS_SWITCH_URL', 'https://devsrv.cspay.app/v1'),
        'cashout_url' => env('MOJO_DEV_CASHOUT_URl', 'https://devsrv.cspay.app/v2/api'),
        'app_id' => env('MOJO_DEV_APP_ID'),
        'app_key' => env('MOJO_DEV_APP_KEY'),
    ],

    'live' => [
        'cross_switch_url' => env('MOJO_LIVE_CROSS_SWITCH_URL', 'https://livesrv.cspay.app/v1'),
        'cashout_url' => env('MOJO_LIVE_CASHOUT_URl', 'https://livesrv.cspay.app/v2/api'),
        'app_id' => env('MOJO_LIVE_APP_ID'),
        'app_key' => env('MOJO_LIVE_APP_KEY'),
    ],
];
