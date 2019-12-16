<?php

namespace App\Auth;

use App\Cognito\CognitoClient;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class CognitoGuard extends SessionGuard implements StatefulGuard
{
    /**
     * @var CognitoClient
     */
    protected $client;

    protected $provider;

    protected $session;

    protected $request;

    /**
     * CognitoGuard constructor.
     * @param string $name
     * @param CognitoClient $client
     * @param UserProvider $provider
     * @param Session $session
     * @param Request|null $request
     */
    public function __construct(
        string $name,
        CognitoClient $client,
        UserProvider $provider,
        Session $session,
        ?Request $request = null
    ) {
        $this->client = $client;
        $this->provider = $provider;
        $this->session = $session;
        $this->request = $request;
        parent::__construct($name, $provider, $session, $request);
    }

    /**
     * register
     * ユーザーを新規登録
     * @param $email
     * @param $pass
     * @param array $attributes
     * @return mixed
     */
    public function register($email, $pass, $attributes = [])
    {
        return $this->client->register($email, $pass, $attributes);
    }

    /**
     * getCognitoUser
     * メールアドレスからCognitoのユーザー名を取得
     */
    public function getCognitoUser($email)
    {
        return $this->client->getUser($email);
    }

    /**
     * @param mixed $user
     * @param array $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        $result = $this->client->authenticate($credentials['email'], $credentials['password']);

        if ($result && $user instanceof Authenticatable) {
            return true;
        }

        return false;
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $credentials
     * @param  bool   $remember
     * @throws
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false)
    {
        $this->fireAttemptEvent($credentials, $remember);

        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            $this->login($user, $remember);
            return true;
        }

        $this->fireFailedEvent($user, $credentials);

        return false;
    }
}
