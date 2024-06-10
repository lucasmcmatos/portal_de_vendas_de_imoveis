<?php
namespace App\Http\Middleware;
use \Exception;

class Maintenance {
    public function handle($request, $next) {
        throw new Exception("testando", 505);
        return $next($request);
    }
}