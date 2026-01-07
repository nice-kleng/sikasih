<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIbuHamilActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('app.login');
        }

        $user = auth()->user();

        // Check if user has ibu_hamil role
        if (!$user->hasRole('ibu_hamil')) {
            auth()->logout();
            return redirect()->route('app.login')->with('error', 'Akses ditolak. Hanya untuk ibu hamil.');
        }

        // Allow access for all authenticated ibu hamil
        // Limited features will be handled at controller/view level based on status
        return $next($request);
    }
}
