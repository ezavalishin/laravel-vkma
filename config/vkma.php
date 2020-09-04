<?php

return [
    'app_id' => env('VKMA_APP_ID'),
    'app_secret' => env('VKMA_APP_SECRET'),
    'service_key' => env('VKMA_SERVICE_KEY'),

    'options' => [
        'model' => \App\User::class,
        'vk_id_key' => 'vk_user_id'
    ]
];
