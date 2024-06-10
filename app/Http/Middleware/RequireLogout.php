<?php
namespace App\Http\Middleware;
require_once(__DIR__."/../../Session/Login.php");

use \Exception;
use \App\Session\Login;

class RequireLogout {
    public function handle($request, $next) {
        if (Login::isLogged()) {
            $request->getRouter()->redirect("/news");
        }

        return $next($request);
    }
}