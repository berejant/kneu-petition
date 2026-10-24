<?php

namespace Kneu\Petition\Socialite;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

/**
 * OAuth2-провайдер auth.kneu.edu.ua
 *
 * Перенесено з пакету kneu/laravel-socialite-provider, який не підтримує сучасні версії Laravel.
 */
class KneuProvider extends AbstractProvider
{
    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://auth.kneu.edu.ua/oauth', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://auth.kneu.edu.ua/oauth/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://auth.kneu.edu.ua/api/user/me', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id'],
            'email' => $user['email'],
            'first_name' => $user['first_name'],
            'middle_name' => $user['middle_name'],
            'last_name' => $user['last_name'],
            'name' => $user['last_name'] . ' ' . $user['first_name'] . ' ' . $user['middle_name'],
            'type' => $user['type'],
            'teacher_id' => Arr::get($user, 'teacher_id'), // optional field
            'department_id' => Arr::get($user, 'department_id'), // optional field
            'student_id' => Arr::get($user, 'student_id'), // optional field
            'group_id' => Arr::get($user, 'group_id'), // optional field
            'sex' => Arr::get($user, 'sex'), // optional field
        ]);
    }

    public function logoutRedirect($redirectUri)
    {
        $url = 'https://auth.kneu.edu.ua/oauth/logout?' . http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => url($redirectUri)
        ], '', '&', $this->encodingType);

        return new RedirectResponse($url);
    }
}
