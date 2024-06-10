<?php
namespace App\Http\Middleware;
require_once(__DIR__."/../../Session/Login.php");

use \Exception;
use \App\Session\Login;

class RequireLogin {
    public function handle($request, $next) {
        if (!Login::isLogged()) {
            $request->getRouter()->redirect("/");
        }

        return $next($request);
    }
}