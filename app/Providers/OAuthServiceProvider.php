<?php

namespace App\Providers;

use App\Service\TokenStorage;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Grant\RefreshToken;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

class OAuthServiceProvider extends ServiceProvider
{
    protected GenericProvider $provider;

    public function boot(): void
    {
        $this->provider = new GenericProvider([
            'clientId' => config('services.oauth.client_id'),
            'clientSecret' => config('services.oauth.client_secret'),
            'redirectUri' => config('services.oauth.redirect_uri'),
            'urlAuthorize' => config('services.oauth.url_authorize'),
            'urlAccessToken' => config('services.oauth.url_access_token'),
            'urlResourceOwnerDetails' => config('services.oauth.url_resource_owner_details'),
        ]);
    }

    public function register(): void
    {
        $this->app->singleton('oauth.service', function ($app) {
            return $this;
        });
    }

    public function getToken(): AccessTokenInterface|AccessToken
    {
        $token = TokenStorage::get();
        if ($token->hasExpired()) {

            $newAccessToken = $this->provider->getAccessToken(new RefreshToken(), [
                'refresh_token' => $token->getRefreshToken(),
            ]);

            TokenStorage::save($newAccessToken);
            return $newAccessToken;
        }

        return $token;
    }
}
