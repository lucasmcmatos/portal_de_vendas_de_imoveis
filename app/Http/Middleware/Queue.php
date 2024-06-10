<?php
namespace App\Http\Middleware;

use \Exception;

class Queue {
    // GLOBAL MIDDLEWARES (RUN IN ALL ROUTES)
    private static $default = [];
    private static $map = [];

    private $middlewares = [];
    private $controller;
    private $controllerArgs = [];

    public function __construct($middlewares, $controller, $controllerArgs) {
        $this->middlewares = array_merge(self::$default, $middlewares);
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    public static function setMap($map) {
        self::$map = $map;
    }
     
    public static function setDefault($default) {
        self::$default = $default;
    }

    public function next($request) {
        // CHECK IF MIDDLEWARE QUEUE IS EMPTY
        if (empty($this->middlewares)) return call_user_func_array($this->controller, $this->controllerArgs);

        // MIDDLEWARE 
        $middleware = array_shift($this->middlewares);

        // CHECK MAPPING 
        if (!isset(self::$map[$middleware])) {
            throw new Exception("Este middleware não foi definido", 500);
        }
       
        // NEXT
        $queue = $this;
        $next = function ($request) use($queue) {
            return $queue->next($request);
        };

        return (new self::$map[$middleware])->handle($request, $next);
    }
}