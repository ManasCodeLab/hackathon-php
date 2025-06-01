<?php
namespace App\Controllers;

use MongoDB\Database;
use Firebase\JWT\JWT;

class AuthController extends BaseController {
    private $users;

    public function __construct(Database $db) {
        parent::__construct($db);
        $this->users = $this->db->selectCollection('users');
    }

    public function handleRequest() {
        switch ($this->getRequestMethod()) {
            case 'POST':
                $this->login();
                break;
            default:
                $this->sendResponse(['message' => 'Method not allowed'], 405);
                break;
        }
    }

    private function login() {
        $data = $this->getRequestData();
        
        if (!isset($data['email']) || !isset($data['password'])) {
            $this->sendResponse(['message' => 'Email and password are required'], 400);
            return;
        }

        $user = $this->users->findOne(['email' => $data['email']]);

        if (!$user || !password_verify($data['password'], $user->password)) {
            $this->sendResponse(['message' => 'Invalid credentials'], 401);
            return;
        }

        $token = JWT::encode([
            'user_id' => (string) $user->_id,
            'email' => $user->email
        ], $_ENV['JWT_SECRET'], 'HS256');

        $this->sendResponse([
            'token' => $token,
            'user' => [
                'id' => (string) $user->_id,
                'email' => $user->email
            ]
        ]);
    }
} 