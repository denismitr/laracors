<?php

namespace Denismitr\Laracors;

use Closure;

class Cors
{
    const CONFIG_KEY = 'laracors';

    protected $allAllowedMethods = [
        'HEAD', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'
    ];

    protected $allowedHeaders = [
        'Content-Type', 'Accept', 'Authorization', 'Location', "Origin", 'Requested'
    ];

    protected $allowedOrigins = [
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

        $this->configure();

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

        foreach ($this->allowedOrigins as $allowedOrigin) {
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
            return implode(', ', $this->allAllowedMethods);
        }

        $allowedMethods = [];
        $methods = explode(',', $methods);

        foreach ($methods as $method) {
            if ( in_array($method, $this->allAllowedMethods) ) {
                $allowedMethods[] = $method;
            }
        }

        return implode(', ', $allowedMethods);
    }

    protected function getAllowedHeaders()
    {
        return implode(', ', $this->allowedHeaders);
    }

    protected function configure()
    {
        $config = config(self::CONFIG_KEY);

        $allowedOrigins = $config['allowed_origins'] ?? null;

        $this->allowedOrigins = $allowedOrigins ?: $this->allowedOrigins;

        $allAllowedMethods = $config['all_allowed_methods'] ?? null;

        $this->allAllowedMethods = $allAllowedMethods ?: $this->allAllowedMethods;

        $allowedHeaders = $config['allowed_headers'] ?? null;

        $this->allowedHeaders = $allowedHeaders ?: $this->allowedHeaders;
    }
}
