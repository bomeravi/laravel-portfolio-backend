<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomCors
{
    /**
     * Allowed domains for CORS
     *
     * @var array
     */
    protected $allowedDomains = [
        'https://saroj.name.np',
        // Add more domains as needed
        // 'https://example.com',
        'http://localhost:3000',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $origin = $request->headers->get('Origin');

        // Check if origin is allowed
        $isAllowed = in_array($origin, $this->allowedDomains);

        // Handle preflight OPTIONS request - return immediately
        if ($request->getMethod() === 'OPTIONS') {
            $response = response('', 200);

            if ($isAllowed) {
                return $this->addCorsHeaders($response, $origin);
            }

            return $response;
        }

        // Continue with the request
        $response = $next($request);

        // Add CORS headers to actual response
        if ($isAllowed) {
            return $this->addCorsHeaders($response, $origin);
        }

        return $response;
    }

    /**
     * Add CORS headers to response
     *
     * @param Response $response
     * @param string $origin
     * @return Response
     */
    protected function addCorsHeaders(Response $response, string $origin): Response
    {
        $response->headers->set('Access-Control-Allow-Origin', $origin);
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin, X-CSRF-TOKEN');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Max-Age', '3600');

        return $response;
    }
}
