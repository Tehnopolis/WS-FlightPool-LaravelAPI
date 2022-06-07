<?php
 
namespace App\Http\Middleware;
 
use Illuminate\Http\Request;

use Closure;
use Str;
 
class EnsureBearer
{
    public function handle(Request $request, Closure $next)
    {
        if (null !== $request->header('Authorization') && Str::startsWith($request->header('Authorization'), 'Bearer')) {
            return $next($request);
        }
        else {
            return response([
                'code' => 301,
                'message' => 'Unauthorized'
            ], 301);
        }
    }
}