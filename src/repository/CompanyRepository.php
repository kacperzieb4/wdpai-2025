<?php

require_once 'Repository.php';

class CompanyRepository extends Repository
{
    public function getAll()
    {
        $stmt = $this->database->connect()->prepare("
            SELECT * FROM companies ORDER BY id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $name)
    {
        $stmt = $this->database->connect()->prepare("
            INSERT INTO companies (name) VALUES (:name)
        ");

        $stmt->execute([':name' => $name]);
    }
}
