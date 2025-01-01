<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        // dump('checking user role: '. $role);
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        
        abort(code: 403);
        // return redirect('/')->with('error', 'Access denied. Admin privileges required.');
        // return redirect('/books')->with('error', 'Access denied. Admin privileges required.');

        // if (Auth::check() && Auth::user()->role === 'user') {
        //     return $next($request);
        // }
        
        // return redirect('/')->with('error', 'Access denied. User privileges required.');
    }
}
