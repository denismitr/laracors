<?php

namespace Denismitr\Cors;

use Closure;

class CorsMiddleware
{
    protected static $allowedMethods = [
        'HEAD', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'
    ];

    protected static $allowedHeaders = [
        'Content-Type', 'Accept', 'Authorization', 'Location', "Origin", 'Requested'
    ];

    protected static $allowedOrigins = [
        "/.+/"
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $methods = 'all')
    {
        $response = $next($request);

        if ($origin = $this->getRequestOrigin($request)) {
            $headers = [
                'Access-Control-Allow-Origin' => $origin,
                'Access-Control-Allow-Methods' => $this->getAllowedMethods($methods),
                'Access-Control-Allow-Headers' => $this->getAllowedHeaders()
            ];

            if ($request->getMethod() === 'OPTIONS') {
                return response(null, 200, $headers);
            }

            foreach ($headers as $key => $value) {
                $response->header($key, $value);
            }
        }

        return $response;
    }

    protected function getRequestOrigin($request)
    {
        $origin = $request->headers->get('Origin');

        foreach (self::$allowedOrigins as $allowedOrigin) {
            if ( preg_match_all($allowedOrigin, $origin) === 1) {
                return $origin;
            }
        }

        return null;
    }

    protected function getAllowedMethods(string $methods)
    {
        $methods = strtoupper($methods);

        if ($methods === 'ALL') {
            return implode(', ', self::$allowedMethods);
        }

        $allowedMethods = [];
        $methods = explode(',', $methods);

        foreach ($methods as $method) {
            if ( in_array($method, self::$allowedMethods) ) {
                $allowedMethods[] = $method;
            }
        }

        return implode(', ', $allowedMethods);
    }

    protected function getAllowedHeaders()
    {
        return implode(', ', self::$allowedHeaders);
    }
}
