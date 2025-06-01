<?php
namespace App\Controllers;

use MongoDB\Database;

class RegistrationController extends BaseController {
    private $users;

    public function __construct(Database $db) {
        parent::__construct($db);
        $this->users = $this->db->selectCollection('users');
    }

    public function handleRequest() {
        switch ($this->getRequestMethod()) {
            case 'POST':
                $this->register();
                break;
            default:
                $this->sendResponse(['message' => 'Method not allowed'], 405);
                break;
        }
    }

    private function register() {
        $data = $this->getRequestData();
        
        if (!isset($data['email']) || !isset($data['password'])) {
            $this->sendResponse(['message' => 'Email and password are required'], 400);
            return;
        }

        // Check if user already exists
        $existingUser = $this->users->findOne(['email' => $data['email']]);
        if ($existingUser) {
            $this->sendResponse(['message' => 'User already exists'], 409);
            return;
        }

        // Create new user
        $user = [
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'created_at' => new \MongoDB\BSON\UTCDateTime()
        ];

        $result = $this->users->insertOne($user);

        $this->sendResponse([
            'message' => 'User registered successfully',
            'user_id' => (string) $result->getInsertedId()
        ], 201);
    }
} 