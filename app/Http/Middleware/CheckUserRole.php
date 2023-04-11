<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = backpack_auth()->user();

        if ($user && $user->role && in_array($user->role->name, $roles)) {
            return $next($request);
        }

        return $this->respondToUnauthorizedRequest($request);
    }


    /**
     * Answer to unauthorized access request.
     *
     */
    private function respondToUnauthorizedRequest(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response(trans('backpack::base.unauthorized'), 401);
        } else {
            return redirect()->guest(backpack_url('login'));
        }
    }

}
