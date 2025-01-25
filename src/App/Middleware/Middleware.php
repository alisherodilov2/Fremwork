<?php
namespace App\Middleware;

class Middleware {
    public function handle($request, $next) {
        return $next($request);
    }
}
