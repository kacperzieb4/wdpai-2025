<?php

require_once 'Repository.php';

class RoleRepository extends Repository
{
    public function getAll()
    {
        $stmt = $this->database->connect()->prepare("
            SELECT * FROM roles
        ");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNameById(int $id): ?string
    {
        $stmt = $this->database->connect()->prepare("
            SELECT name FROM roles WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['name'] ?? null;
    }

}
