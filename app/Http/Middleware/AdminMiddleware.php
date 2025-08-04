<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->isAdmin()) {
            abort(403, 'Bu sayfaya sadece yöneticiler erişebilir.');
        }

        return $next($request);
    }
}