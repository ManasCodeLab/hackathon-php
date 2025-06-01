<?php
namespace App\Controllers;

use MongoDB\Database;
use App\Models\Registration;
use App\Middleware\AuthMiddleware;

class HackathonController extends BaseController {
    private $registration;

    public function __construct(Database $db) {
        parent::__construct($db);
        $this->registration = new Registration($db);
    }

    public function handleRequest() {
        $user = AuthMiddleware::authenticate();

        switch ($this->getRequestMethod()) {
            case 'POST':
                $this->createRegistration($user);
                break;
            case 'GET':
                $this->getRegistration($user);
                break;
            case 'PUT':
                $this->updateRegistration($user);
                break;
            default:
                $this->sendResponse(['message' => 'Method not allowed'], 405);
                break;
        }
    }

    private function createRegistration($user) {
        $data = $this->getRequestData();
        $data['user_id'] = $user->user_id;

        if (!isset($data['project_name']) || !isset($data['project_description'])) {
            $this->sendResponse(['message' => 'Project name and description are required'], 400);
            return;
        }

        $registrationId = $this->registration->create($data);
        $this->sendResponse([
            'message' => 'Registration created successfully',
            'registration_id' => (string) $registrationId
        ], 201);
    }

    private function getRegistration($user) {
        $registration = $this->registration->findByUserId($user->user_id);
        
        if (!$registration) {
            $this->sendResponse(['message' => 'Registration not found'], 404);
            return;
        }

        $this->sendResponse($registration);
    }

    private function updateRegistration($user) {
        $data = $this->getRequestData();
        $registration = $this->registration->findByUserId($user->user_id);

        if (!$registration) {
            $this->sendResponse(['message' => 'Registration not found'], 404);
            return;
        }

        if (!isset($data['project_name']) || !isset($data['project_description'])) {
            $this->sendResponse(['message' => 'Project name and description are required'], 400);
            return;
        }

        $success = $this->registration->update($registration->_id, $data);
        
        if ($success) {
            $this->sendResponse(['message' => 'Registration updated successfully']);
        } else {
            $this->sendResponse(['message' => 'Failed to update registration'], 500);
        }
    }
} 