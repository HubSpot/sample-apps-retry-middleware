<?php

namespace Helpers;

use HubSpot\Factory;
use Repositories\TokensRepository;

class OAuth2Helper
{
    public const APP_REQUIRED_SCOPES = ['crm.objects.contacts.read'];
    public const CALLBACK_PATH = '/oauth/callback';
    public const SESSION_TOKENS_KEY = 'tokens';

    public static function getClientId(): string
    {
        return getEnvOrException('HUBSPOT_CLIENT_ID');
    }

    public static function getClientSecret(): string
    {
        return getEnvOrException('HUBSPOT_CLIENT_SECRET');
    }

    public static function getRedirectUri(): string
    {
        return UrlHelper::generateServerUri().static::CALLBACK_PATH;
    }

    public static function getScope(): array
    {
        return static::APP_REQUIRED_SCOPES;
    }

    public static function getExpiresAt(int $expiresIn): int
    {
        return time() + $expiresIn * 0.95;
    }

    public static function isAuthenticated(): bool
    {
        return !empty(TokensRepository::getToken());
    }

    public static function refreshAndGetAccessToken(): string
    {
        $token = TokensRepository::getToken();

        if (empty($token)) {
            throw new \Exception('Please authorize via OAuth2');
        }

        if (time() > $token['expires_at']) {
            $response = Factory::create()
                ->auth()->oAuth()->tokensApi()->create(
                    'refresh_token',
                    null,
                    null,
                    static::getClientId(),
                    static::getClientSecret(),
                    $token['refresh_token']
                )
            ;

            TokensRepository::save([
                'refresh_token' => $response->getRefreshToken(),
                'access_token' => $response->getAccessToken(),
                'expires_in' => $response->getExpiresIn(),
                'expires_at' => static::getExpiresAt($response->getExpiresIn()),
            ]);

            return $response->getAccessToken();
        }

        return $token['access_token'];
    }
}
