<?php

namespace App\Service;

use Illuminate\Support\Facades\Cache;
use League\OAuth2\Client\Token\AccessToken;

class TokenStorage
{
    public static function get(): AccessToken
    {
        return new AccessToken(json_decode(Cache::get('oauth_access_token'), true) ?? [
            'access_token' => 'noneExistent',
            'expires' => 1349067601,
            'refresh_token' => config('mail.mailers.smtp.refresh_token')
        ]);
    }

    public static function save(AccessToken $token): void
    {
        Cache::put('oauth_access_token', json_encode($token->jsonSerialize()), now()->addSeconds($token->getExpires() - time()));
    }
}
