<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Daftar origin yang diizinkan (sesuaikan dengan frontend Anda)
        $allowedOrigins = [
            'http://localhost:3000', // Contoh: React/Vue dev server
            // 'https://domain-anda.com', // Contoh: Production domain
        ];

        $origin = $request->headers->get('Origin');

        // Jika origin ada dalam daftar yang diizinkan, tambahkan header CORS
        if (in_array($origin, $allowedOrigins)) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', $origin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                ->header('Access-Control-Allow-Credentials', 'true'); // Untuk cookie/auth
        }

        return $next($request);
    }
}
