<?php

namespace App\Service;

use App\Entity\User;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Rsa\Sha384;

class MercureCookiesGenerator
{

    private $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function generator(User $user) {

        $token = (new Builder())
                ->set('mercure', ['subscribe' => ["http://monsite.com/user/{$user->getId()}"]])
                ->sign(new Sha384(), $this->secret)
                ->getToken();
        return  `mercureAutorization={$token}; path = /hub; httpOnly;`;
    }
}
