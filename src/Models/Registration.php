<?php
namespace App\Models;

use MongoDB\Database;
use MongoDB\Collection;

class Registration {
    private $collection;

    public function __construct(Database $db) {
        $this->collection = $db->selectCollection('registrations');
    }

    public function create($data) {
        $registration = [
            'user_id' => new \MongoDB\BSON\ObjectId($data['user_id']),
            'team_name' => $data['team_name'] ?? null,
            'team_members' => $data['team_members'] ?? [],
            'project_name' => $data['project_name'],
            'project_description' => $data['project_description'],
            'created_at' => new \MongoDB\BSON\UTCDateTime()
        ];

        $result = $this->collection->insertOne($registration);
        return $result->getInsertedId();
    }

    public function findByUserId($userId) {
        return $this->collection->findOne(['user_id' => new \MongoDB\BSON\ObjectId($userId)]);
    }

    public function findAll() {
        return $this->collection->find()->toArray();
    }

    public function update($id, $data) {
        $update = [
            '$set' => [
                'team_name' => $data['team_name'] ?? null,
                'team_members' => $data['team_members'] ?? [],
                'project_name' => $data['project_name'],
                'project_description' => $data['project_description'],
                'updated_at' => new \MongoDB\BSON\UTCDateTime()
            ]
        ];

        $result = $this->collection->updateOne(
            ['_id' => new \MongoDB\BSON\ObjectId($id)],
            $update
        );

        return $result->getModifiedCount() > 0;
    }
} 