<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtParser
{
    protected $claims;

    public function __construct(string $token)
    {
        JWT::$leeway = $this->getLeeway();
        $this->claims = JWT::decode($token, new Key($this->getPublicKey(), $this->supportedAlgos()));
    }

    protected function getLeeway()
    {
        return config('jwt.leeway');
    }

    protected function getPublicKey(): string
    {
        return file_get_contents(config('jwt.public_key'));
    }

    protected function supportedAlgos()
    {
        return config('jwt.supported_algos');
    }

    public static function loadFromToken(string $token)
    {
        return new self($token);
    }

    public function getIssuedBy()
    {
        return $this->getClaim('iss');
    }

    protected function getClaim(string $name)
    {
        return $this->claims->{$name} ?? null;
    }

    public function getIssuedAt()
    {
        return $this->getClaim('iat');
    }

    public function getRelatedTo()
    {
        return $this->getClaim('sub');
    }

    public function getAudience()
    {
        return $this->getClaim('aud');
    }

    public function getExpiresAt()
    {
        return $this->getClaim('exp');
    }

    public function getIdentifiedBy()
    {
        return $this->getClaim('jti');
    }

    public function getCanOnlyBeUsedAfter()
    {
        return $this->getClaim('nbf');
    }

    protected function getAlgo()
    {
        return config('jwt.encrypt_algo');
    }
}
