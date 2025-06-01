<?php
namespace App\Models;

use MongoDB\Database;
use MongoDB\Collection;

class User {
    private $collection;

    public function __construct(Database $db) {
        $this->collection = $db->selectCollection('users');
    }

    public function findByEmail($email) {
        return $this->collection->findOne(['email' => $email]);
    }

    public function create($data) {
        $user = [
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'created_at' => new \MongoDB\BSON\UTCDateTime()
        ];

        $result = $this->collection->insertOne($user);
        return $result->getInsertedId();
    }

    public function findById($id) {
        return $this->collection->findOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
    }
} 