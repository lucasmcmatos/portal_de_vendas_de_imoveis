<?php
namespace App\Http\Middleware;
require_once(__DIR__."/../../Session/Login.php");

use \Exception;
use \App\Session\Login;

class RequireAdmin {
    public function handle($request, $next) {
        if (!Login::isAdmin()) {
            $request->getRouter()->redirect("/news");
        }

        return $next($request);
    }
}