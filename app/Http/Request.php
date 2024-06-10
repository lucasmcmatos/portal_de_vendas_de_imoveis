<?php
namespace App\Http;

class Request {


    private $httpMethod;
    private $uri;
    private $postVars = [];
    private $queryParams = [];
    private $headers = [];
    private $router;
    private $bodyVars = [];



    public function __construct($router) {
        $this->router = $router;
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER["REQUEST_METHOD"] ?? "";
        $this->setUri();
        $this->setBodyVars();
    }



    private function setUri() {
        $this->uri = $_SERVER["REQUEST_URI"] ?? "";
        $xURI = explode("?", $this->uri);
        $this->uri = $xURI[0];
    }



    private function setBodyVars() {
        $this->bodyVars = (array) json_decode(file_get_contents('php://input'));
    }



    public function getHttpMethod() {
        return $this->httpMethod;
    }



    public function getUri() {
        return $this->uri;
    }


    public function getPostVars() {
        return $this->postVars;
    }



    public function getQueryParams() {
        return $this->queryParams;
    }



    public function getBodyVars() {
        return $this->bodyVars;
    }

    

    public function getRouter() {
        return $this->router;
    }

}