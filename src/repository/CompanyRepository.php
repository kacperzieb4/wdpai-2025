<?php

require_once 'Repository.php';

class CompanyRepository extends Repository
{
    public function getAll()
    {
        $stmt = $this->database->connect()->query("
            SELECT * FROM companies ORDER BY name
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id)
    {
        $stmt = $this->database->connect()->prepare("
            SELECT * FROM companies WHERE id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(string $name)
    {
        $stmt = $this->database->connect()->prepare("
            INSERT INTO companies (name) VALUES (:name)
        ");
        $stmt->execute([':name' => $name]);
    }

    public function update(int $id, string $name)
    {
        $stmt = $this->database->connect()->prepare("
            UPDATE companies SET name = :name WHERE id = :id
        ");
        $stmt->execute([
            ':name' => $name,
            ':id' => $id
        ]);
    }

    public function delete(int $id)
    {
        $stmt = $this->database->connect()->prepare("
            DELETE FROM companies WHERE id = :id
        ");
        $stmt->execute([':id' => $id]);
    }

    public function deleteWithUsers(int $companyId)
    {
        $db = $this->database->connect();

        $db->beginTransaction();

        try {
            $stmt = $db->prepare("
                DELETE FROM users WHERE company_id = :id
            ");
            $stmt->execute([':id' => $companyId]);

            $stmt = $db->prepare("
                DELETE FROM companies WHERE id = :id
            ");
            $stmt->execute([':id' => $companyId]);

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function getFinchStudioId(): int
    {
        $stmt = $this->database->connect()->prepare("
            SELECT id FROM companies WHERE is_protected = true LIMIT 1
        ");
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }



}
