<?php
namespace App\Config;

use MongoDB\Client;

class Database {
    private $client;
    private $database;

    public function __construct() {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        // Get MongoDB URI from environment
        $uri = $_ENV['MONGODB_URI'];
        
        // Validate MongoDB URI
        if (empty($uri)) {
            throw new \Exception('MONGODB_URI environment variable is not set');
        }

        // Add MongoDB options for better reliability
        $options = [
            'retryWrites' => true,
            'w' => 'majority',
            'maxPoolSize' => 50,
            'minPoolSize' => 10,
            'serverSelectionTimeoutMS' => 5000,
            'connectTimeoutMS' => 10000
        ];

        try {
            $this->client = new Client($uri, $options);
            $this->database = $this->client->selectDatabase('codegenx');
            
            // Test the connection
            $this->client->listDatabases();
        } catch (\Exception $e) {
            throw new \Exception('Failed to connect to MongoDB: ' . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->database;
    }
} 