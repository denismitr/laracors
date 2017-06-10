<?php

/*
|--------------------------------------------------------------------------
| Cross Origin Resource Sharing Middleware Configuration
|--------------------------------------------------------------------------
|
| Use regex to configure allowed origins e.g.:
| "/https:\/\/(www\.)?([a-z0-9]+\.)?mydomain\.(com|fr)/"
| "/http(s)?:\/\/(www\.)?localhost(:[0-9]+)?/"
|
*/

return [
    'allowed_origins' => [
        "/.+/"
    ],

    'all_allowed_methods' => [
        'HEAD', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'
    ],

    'allowed_headers' => [
        'Content-Type', 'Accept', 'Authorization', 'Location', "Origin", 'Requested'
    ]
];