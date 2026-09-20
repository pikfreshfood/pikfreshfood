<?php

return [
    'vapid' => [
        'subject' => env('WEB_PUSH_SUBJECT', 'mailto:support@pikfreshfood.com'),
        'public_key' => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
    ],
];
