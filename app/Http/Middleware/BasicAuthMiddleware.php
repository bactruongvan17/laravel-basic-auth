<?php

namespace App\Http\Middleware;

use Closure;

class BasicAuthMiddleware
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $header = $request->header("Authorization");
        if (empty($header)) {
            return response()->json([], 401, [
                'www-authenticate' => 'Basic realm="user_pages"'
            ]);
        }

        $cridentials = trim(str_replace('Basic','', $header));
        list($username, $password) = explode(':', base64_decode($cridentials));
        
        if (empty($username) || empty($password)) {
            return response()->json([], 401, [
                'www-authenticate' => 'Basic realm="user_pages"'
            ]);
        }

        if ($username === 'admin' && $password === 'admin') {
            return $next($request);
        }

        return response()->json([], 401, [
            'www-authenticate' => 'Basic realm="user_pages"'
        ]);
    }

}