<?php

require_once 'Repository.php';

<<<<<<< Updated upstream
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

=======
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
>>>>>>> Stashed changes
        $stmt->execute([':name' => $name]);
    }
}
