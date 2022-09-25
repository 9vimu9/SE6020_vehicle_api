<?php

namespace App\Services\MicroServiceToken;

use App\Models\User;
use DateTimeZone;
use Exception;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class TokenValidator
{
    private string $rowToken;

    public function __construct(string $rowToken)
    {
        $this->rowToken = $rowToken;
    }

    public function validate(): bool
    {
        return count($this->getDataFromToken($this->rowToken)) > 0;

    }

    private function getDataFromToken(): array
    {
        try {
            $key = InMemory::plainText(config('microservice_token.secret'));
            $token = (new JwtFacade())->parse(
                $this->rowToken,
                new Constraint\SignedWith(new Sha256(), $key),
                new Constraint\StrictValidAt(
                    new SystemClock(new DateTimeZone('UTC'))
                )
            );
            return $token->claims()->all();
        } catch (Exception $exception) {
            return [];

        }
    }

    public function getUserFromToken(): User
    {
        $data = $this->getDataFromToken($this->rowToken);
        if(!array_key_exists("user_id",$data) || !array_key_exists("role",$data)){
            throw new ResourceNotFoundException();
        }
        $user = new User();
        $user->id = $data["user_id"];
        $user->role = $data["role"];
        return $user;
    }

    public function saveUserFromToken(User $user=null): void
    {
        if(!$user){
            $user = $this->getUserFromToken();
        }
        auth()->login($user, false);
    }

}
