<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseResource;
use App\Models\User;
use App\Services\JwtBuilder;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * @OA\Info(
 *    title="Pet Shop API By Fasna",
 *    version="1.0.0",
 * )
 *  * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *     name="bearerAuth",
 *     bearerFormat="JWT",
 *     in="header"
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

    protected function resourceNotFound($message)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => 0,
                'data' => [],
                'error' => $message,
                'errors' => [],
                'trace' => []
            ], ResponseAlias::HTTP_NOT_FOUND)
        );
    }

    protected function notEnoughPrivilege()
    {
        throw new HttpResponseException(
            response()->json([
                'success' => 0,
                'data' => [],
                'error' => "Unauthorized: Not enough privileges",
                'errors' => [],
                'trace' => []
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

    protected function authenticationFailed()
    {
        throw new HttpResponseException(
            response()->json([
                'success' => 0,
                'data' => [],
                'error' => "Failed to authenticate user",
                'errors' => [],
                'trace' => []
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

    protected function emptySuccessResponse()
    {
       return  (new BaseResource([]))
            ->withSuccess(1)
            ->withError(null)
            ->withErrors([])
            ->withExtra([]);
    }
}
