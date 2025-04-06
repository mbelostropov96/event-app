<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->role !== UserRole::ADMIN->value) {
            return response()->json(['message' => 'Доступ запрещен'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
