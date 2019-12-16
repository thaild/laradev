<?php

return [
    'region' => env('AWS_COGNITO_REGION'),
    'version' => env('AWS_COGNITO_VERSION', '2016-04-18'),
    'credentials' => [
        'key' => env('AWS_COGNITO_KEY', ''),
        'secret' => env('AWS_COGNITO_SECRET', ''),
    ],
    'app_client_id' => env('AWS_COGNITO_CLIENT_ID'), // 作成したクライアントID
    'app_client_secret' => env('AWS_COGNITO_CLIENT_SECRET'), // 作成したクライアントシークレット
    'user_pool_id' => env('AWS_COGNITO_USER_POOL_ID'), // ユーザープールのID
];
