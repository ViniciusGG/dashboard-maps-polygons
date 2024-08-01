<?php

namespace App\Http\Middleware;
use Closure;


class WebhookAzulfy
{
    public function handle($request, Closure $next)
    {
        //Bearer token
        if ($request->header('Authorization') == config('app.bearer_azulfy')) {
            return $next($request);
        }

        return response()->json(['message' => 'Webhook não autenticado'], 401);
    }
}
