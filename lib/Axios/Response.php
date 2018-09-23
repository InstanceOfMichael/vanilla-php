<?php

namespace Axios;

class Response {
    
    public $body;
    public $status;
    public $headers;
    
    public function __construct($body, $status, $headers) {
        $this->body = $body;
        $this->status = $status;
        $this->headers = $headers;
    }
    
    public function toDOM(): DOMDocument {
        $dom = new DOMDocument;
        $dom->loadHTML($this->body);
        return $dom;
    }
    
    public function getHeader($key) {
        $key = strtolower($key);
        if (array_key_exists($key, $this->headers)) {
            return $this->headers[$key];
        }
        return null;
    }
    
    public function toArray() {
        return json_decode($this->body, $associative = true);
    }
    
    public function toStdClass() {
        return json_decode($this->body);
    }
}
