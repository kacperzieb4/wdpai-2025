<?php

require_once 'Repository.php';

class CompanyRepository extends Repository {

    public function getAll()
    {
        $stmt = $this->database->connect()->prepare(
            "SELECT * FROM companies ORDER BY name"
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name)
    {
        $stmt = $this->database->connect()->prepare(
            "INSERT INTO companies (name) VALUES (:name)"
        );

        $stmt->execute([':name' => $name]);
    }
}
