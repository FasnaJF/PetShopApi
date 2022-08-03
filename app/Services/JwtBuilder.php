<?php

namespace App\Services;

use Carbon\CarbonInterface;
use Firebase\JWT\JWT;

class JwtBuilder
{
    protected $claims;

    public function issuedBy($val): self
    {
        return $this->registerClaim('iss', $val);
    }

    public function issuedAt(CarbonInterface $dateTime)
    {
        return $this->registerClaim('iat', $dateTime->timestamp);
    }

    public function relatedTo($val)
    {
        return $this->registerClaim('sub', $val);
    }


    public function audience($name)
    {
        return $this->registerClaim('aud', $name);
    }

    public function expiresAt(CarbonInterface $dateTime)
    {
        return $this->registerClaim('exp', $dateTime->timestamp);
    }

    public function identifiedBy($val)
    {
        return $this->registerClaim('jti', $val);
    }

    public function canOnlyBeUsedAfter(CarbonInterface $carbon)
    {
        return $this->registerClaim('nbf', $carbon->timestamp);
    }

    public function withClaim($name, $value)
    {
        return $this->registerClaim($name, $value);
    }

    public function withClaims(array $claims): self
    {
        foreach ($claims as $name => $value) {
            $this->withClaim($name, $value);
        }
        return $this;
    }

    public function getToken()
    {
        return JWT::encode($this->claims, $this->getPrivateKey(), $this->getAlgo());
    }

    protected function getPrivateKey(): string
    {

        return file_get_contents(config('jwt.private_key'));
    }

    protected function getAlgo()
    {
        return config('jwt.encrypt_algo');
    }

    protected function registerClaim(string $name, string $val): self
    {
        $this->claims[$name] = $val;
        return $this;
    }
}
