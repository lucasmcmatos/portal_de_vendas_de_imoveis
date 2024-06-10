<?php
namespace App\Http;

class Response {
    private $httpCode = 200;
    private $headers = [];
    private $contentType = "text/html";
    private $content;


    public function __construct($httpCode, $content, $contentType = "text/html") {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
        $this->setNoCors();
    }



    public function setContentType($contentType) {
        $this->contentType = $contentType;
        $this->addHeader("Content-Type", $contentType);
    }


    //Atenção
    public function setNoCors() {
        $this->addHeader("Access-Control-Allow-Origin", "*");
    }



    public function addHeader($key, $value) {
        $this->headers[$key] = $value;
    }



    public function sendHeaders() {
        http_response_code($this->httpCode);
        foreach ($this->headers as $key => $value) {
            header($key . ": " . $value);
        }

        if ($this->content !== null && is_string($this->content)) {
            $this->addHeader("Content-Length", strlen($this->content));
        }
    }



    public function getCode() {
        return $this->httpCode;
    }



    public function sendResponse() {
        $this->sendHeaders();
        echo $this->content;
        exit;
    }
}


