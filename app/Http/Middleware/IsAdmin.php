<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\JwtParser;
use Closure;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class IsAdmin
{

    public function handle(Request $request, Closure $next)
    {
        $token = $request->get('token') ?? $request->bearerToken();
        if (!$token) {
            throw new HttpResponseException(
                response()->json([
                    'success' => 0,
                    'data' => [],
                    'error' => "Unauthorized",
                    'errors' => [],
                    'trace' => []
                ], ResponseAlias::HTTP_UNAUTHORIZED)
            );
        }
        try {
            $parser = new JwtParser($token);
            $user = User::find($parser->getRelatedTo());

            if ($user->is_admin) {
                return $next($request);
            } else {
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
        } catch (ExpiredException $e) {
            throw new HttpResponseException(
                response()->json([
                    'success' => 0,
                    'data' => [],
                    'error' => "Unauthorized",
                    'errors' => [],
                    'trace' => []
                ], ResponseAlias::HTTP_UNAUTHORIZED)
            );
        }
    }
}
