<?php

namespace App\Validators;

use Illuminate\Auth\AuthManager;

class CognitoUserUniqueValidator
{

    /**
     * @var AuthManager
     */
    private $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    public function validate($attribute, $value, $parameters, $validator)
    {
        $cognitoUser = $this->authManager->getCognitoUser($value);
        if ($cognitoUser) {
            return false;
        }
        return true;
    }
}
