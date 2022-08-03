<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JwtBuilder;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="Pet Shop API By Fasna",
 *    version="1.0.0",
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected function createJwtToken(User $user, CarbonInterface $ttl = null): string
    {
        return (new JwtBuilder())
            ->issuedBy(config('app.url'))
            ->audience(config('app.name'))
            ->issuedAt(now())
            ->canOnlyBeUsedAfter(now()->addMinute())
            ->expiresAt($ttl ?? now()->addSeconds(config('jwt.ttl')))
            ->relatedTo($user->id)
            ->getToken();
    }
}
