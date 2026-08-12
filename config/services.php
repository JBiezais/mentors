<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OAuth (Microsoft Graph)
    |--------------------------------------------------------------------------
    |
    | Client credentials used to obtain an access token for sending emails
    | via the Microsoft Graph API (see App\Service\OAuthService).
    |
    */

    'oauth' => [
        'token_url' => 'https://login.microsoftonline.com/{tenant_id}/oauth2/v2.0/token',
        'tenant_id' => env('OAUTH_TENANT_ID'),
        'client_id' => env('OAUTH_CLIENT_ID'),
        'client_secret' => env('OAUTH_CLIENT_SECRET'),
        'scope' => 'https://graph.microsoft.com/.default',
    ],

];
