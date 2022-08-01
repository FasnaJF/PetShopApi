<?php

namespace App\Repositories\JwtTokenRepository;

use App\Repositories\BaseRepository;
use App\Models\JwtToken;

class JwtTokenRepository extends BaseRepository implements JwtTokenRepositoryInterface
{
    public function __construct(JwtToken $jwtToken)
    {
        $this->model = $jwtToken;
    }

}
