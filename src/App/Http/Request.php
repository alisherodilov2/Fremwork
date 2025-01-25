<?php


namespace App\Http;
class Request {
    public $method;
    public $uri;
    public $query;
    public $body;

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD']; // HTTP method (GET, POST, etc.)
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // URI of the request
        $this->query = $_GET; // Query parameters (GET data)
        $this->body = $_POST; // POST data
    }

    /**
     * Get a specific query parameter from the URL
     */
    public function getQuery($key) {
        return isset($this->query[$key]) ? $this->query[$key] : null;
    }

    /**
     * Get a specific POST parameter
     */
    public function getPost($key) {
        return isset($this->body[$key]) ? $this->body[$key] : null;
    }

    /**
     * Get the full URI with query parameters
     */
    public function fullUri() {
        return $_SERVER['REQUEST_URI']; // Full URI including query string
    }
}
