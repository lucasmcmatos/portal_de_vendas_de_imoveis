<?php
namespace App\Http;
require_once(__DIR__."/Request.php");
require_once(__DIR__."/Response.php");
require_once(__DIR__."/Middleware/Queue.php");

use \Closure;
use \Exception;
use \ReflectionFunction;
use \App\Http\Middleware\Queue as MiddlewareQueue;

class Router {

    private $url = "";
    private $prefix = "";
    private $routes = [];
    private $request
    ;

    public function __construct($url) {
        $this->url = $url;
        $this->request = new Request($this);
        $this->setPrefix();
    }




    private function setPrefix() {
        $parseUrl = parse_url($this->url);
        $this->prefix = $parseUrl["path"] ?? "";
    }




    private function addRoute($method, $route, $params = []) {
        foreach($params as $key => $value) {
            if ($value instanceof Closure) {
                $params["controller"] = $value;
                unset($params[$key]);
            }
        }
       
        //Setup Middlewares
        $params["middlewares"] = $params["middlewares"] ?? [];

        $params["variables"] = [];

        $patternVariable = "/{(.*?)}/";

        if (preg_match_all($patternVariable, $route, $matches)) {
            $route = preg_replace($patternVariable, "(.*?)", $route);
            $params["variables"] = $matches[1];
        }

        $patternRoute = "/^".str_replace("/", "\/", $route)."$/";
        
        $this->routes[$patternRoute][$method] = $params;

    }




    private function getUri() {
        $uri = $this->request->getUri();
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];
        return end($xUri);
    }




    private function getRoute() {
        $uri = $this->getUri();
        $httpMethod = $this->request->getHttpMethod();

        foreach($this->routes as $patternRoute => $methods) {
            if (preg_match($patternRoute, $uri, $matches)) {
                if (isset($methods[$httpMethod])) {
                    unset($matches[0]);

                    $keys = $methods[$httpMethod]["variables"];
                    $methods[$httpMethod]["variables"] = array_combine($keys, $matches);
                    $methods[$httpMethod]["variables"]["request"] = $this->request; 

                    return $methods[$httpMethod];
                }

                throw new Exception("Método não permitido!", 405);
            }

        }

        throw new Exception("Rota não encontrada!", 404);
    }




    public function get($route, $params) {
        return $this->addRoute("GET", $route, $params);
    }




    public function post($route, $params) {
        return $this->addRoute("POST", $route, $params);
    }




    public function put($route, $params) {
        return $this->addRoute("PUT", $route, $params);
    }




    public function delete($route, $params) {
        return $this->addRoute("DELETE", $route, $params);
    }




    public function redirect($route) {
        $url = $this->url.$route;
        header("Location: ".$url);
        exit();
    }




    public function run() {
        try {
            $route = $this->getRoute();

            if (!isset($route["controller"])) {
                throw new Exception("URL não pode ser processada", 404);
            }
            
            $args = [];
            $reflectionFunc = new ReflectionFunction($route["controller"]);
            
            foreach($reflectionFunc->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route["variables"][$name] ?? "";
            }

            return (new MiddlewareQueue($route['middlewares'], $route["controller"], $args))->next($this->request);

            return call_user_func_array($route["controller"], $args);
            
        } catch(Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }
}