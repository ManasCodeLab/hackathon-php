<?php
namespace App\Controllers;

use MongoDB\Database;

abstract class BaseController {
    protected $db;
    protected $requestMethod;
    protected $requestData;

    public function __construct(Database $db) {
        $this->db = $db;
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->requestData = json_decode(file_get_contents('php://input'), true);
    }

    abstract public function handleRequest();

    protected function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data);
    }

    protected function getRequestData() {
        return $this->requestData;
    }

    protected function getRequestMethod() {
        return $this->requestMethod;
    }
} 