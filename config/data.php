<?php

return [
    'apps' => [
        'nany-article' => [
            'serviceName'=> 'Nany Article',
            'supportEmail' => 'contact@nanybot.com',
            'contactEmail' => 'contact@nanybot.com',
            'businessAddress' => 'Mirpur 13, Dhaka',
            'taxPercent' => 4.9,
            'packageUrl' => env('NANY_ARTICLE_PAYMENT_API_ENDPOINT_PRICING', ''),
        ],
    ],

    'mail_sending_api' => 'https://datamatric.com/api/mail/nany/send',
    'mail_sending_api_token' => '8f0a2e0bfb01c3da76e7',
];