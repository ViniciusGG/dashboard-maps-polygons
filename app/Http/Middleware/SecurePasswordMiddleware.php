<?php
/*
 *  ______     __  __     ______     ______     __   __   ______     __
 * /\  == \   /\ \/\ \   /\___  \   /\___  \   /\ \ / /  /\  ___\   /\ \
 * \ \  __<   \ \ \_\ \  \/_/  /__  \/_/  /__  \ \ \'/   \ \  __\   \ \ \____
 *  \ \_____\  \ \_____\   /\_____\   /\_____\  \ \__|    \ \_____\  \ \_____\
 *   \/_____/   \/_____/   \/_____/   \/_____/   \/_/      \/_____/   \/_____/
 *
 * Made By: Mauro Gama
 *
 * ♥ BY Buzzers: BUZZVEL.COM
 * Last Update: 2022.6.20
 */

namespace App\Http\Middleware;

use App\Services\Api\ApiResponseService;
use Closure;
use Illuminate\Http\Request;

class SecurePasswordMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $expiredDate = $user->password_expires_at;

        if (now() >= $expiredDate) {

            $response = new ApiResponseService();
            return $response->errorResponse('Password expired', 100, 400);
        }

        return $next($request);
    }
}
