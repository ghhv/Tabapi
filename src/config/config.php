<?php

/**
 * Configuration options for TAB Oath settings and REST API defaults.
 */
return [

    /*
     * Enter your credentials
     */
    'credentials'    => [
        //Required:
        'clientId'    => env('TAB_CLIENT_ID'),
        'clientSecret' => env('TAB_CLIENT_SECRET'),
        'callbackURI'    => env('TAB_CALLBACK_URI'),
        'apiURL'       => env('TAB_API_URL'),
    ],

    /*
     * Default settings for resource requests.
     * Format can be 'json', 'xml' or 'none'
     * Compression can be set to 'gzip' or 'deflate'
     */
    'defaults'       => [
        'method'          => 'get',
        'format'          => 'json',
        'compression'     => false,
        'compressionType' => 'gzip',
    ],

    /*
     * Where do you want to store access tokens fetched from Tab
     */
    'storage'        => [
        'type'          => 'session', // 'session' or 'cache' are the two options
        'path'          => 'tabapi_', // unique storage path to avoid collisions
        'expire_in'     => 60, // number of minutes to expire cache/session
        'store_forever' => false, // never expire cache/session
    ],

    /*
     * If you'd like to specify an API version manually it can be done here.
     * Format looks like '32.0'
     */
    'version'        => '',

    /*
     * Language
     */
    'language'       => 'en_AU',
];
