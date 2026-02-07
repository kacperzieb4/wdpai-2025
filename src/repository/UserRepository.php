<?php

require_once 'Repository.php';

class UserRepository extends Repository
{
    public function getUserByEmail(string $email)
    {
        $stmt = $this->database->connect()->prepare("
            SELECT u.*, r.name AS role
            FROM users u
            JOIN roles r ON u.role_id = r.id
            WHERE u.email = :email
        ");

        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRoleIdByName(string $name)
    {
        $stmt = $this->database->connect()->prepare("
            SELECT id FROM roles WHERE name = :name
        ");
        $stmt->execute([':name' => $name]);
        return $stmt->fetchColumn();
    }

    public function createInactiveUser(
        string $email,
        string $firstname,
        string $lastname,
        int $roleId,
        int $companyId,
        string $code
    )
    {
        $stmt = $this->database->connect()->prepare("
            INSERT INTO users (email, firstname, lastname, role_id, company_id, activation_code, is_active)
            VALUES (:email, :firstname, :lastname, :role, :company, :code, false)
        ");

        $stmt->execute([
            ':email' => $email,
            ':firstname' => $firstname,
            ':lastname' => $lastname,
            ':role' => $roleId,
            ':company' => $companyId,
            ':code' => $code
        ]);
    }

    public function activateUser(string $email, string $hashedPassword): void
    {
        $db = Database::getInstance()->connect();

        try {
            $db->beginTransaction();
            $db->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');

            $stmt = $db->prepare(
                "UPDATE users 
                SET password = :p, is_active = TRUE 
                WHERE email = :e"
            );
            $stmt->execute([
                ':p' => $hashedPassword,
                ':e' => $email
            ]);

            if ($stmt->rowCount() !== 1) {
                throw new RuntimeException('User not activated');
            }

            $db->commit();
        } catch (Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function getAllUsers()
    {
        $stmt = $this->database->connect()->prepare("
            SELECT u.id, u.firstname, u.lastname, u.email, c.name AS company
            FROM users u
            LEFT JOIN companies c ON u.company_id = c.id
            ORDER BY u.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignUserToCompany(int $userId, int $companyId)
    {
        $stmt = $this->database->connect()->prepare("
            UPDATE users SET company_id = :company WHERE id = :id
        ");
        $stmt->execute([
            ':company' => $companyId,
            ':id' => $userId
        ]);
    }

    public function getAllUsersWithCompany()
    {
        $stmt = $this->database->connect()->prepare("
            SELECT 
                u.id,
                u.firstname,
                u.lastname,
                r.name AS role,
                c.name AS company
            FROM users u
            JOIN roles r ON u.role_id = r.id
            LEFT JOIN companies c ON u.company_id = c.id
            ORDER BY u.lastname
        ");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById(int $id)
    {
        $stmt = $this->database->connect()->prepare("
            SELECT u.*, r.name AS role
            FROM users u
            JOIN roles r ON u.role_id = r.id
            WHERE u.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser(int $id, string $firstname, string $lastname, int $roleId, ?int $companyId)
    {
        $stmt = $this->database->connect()->prepare("
            UPDATE users
            SET firstname = :firstname,
                lastname = :lastname,
                role_id = :role,
                company_id = :company
            WHERE id = :id
        ");

        $stmt->execute([
            ':firstname' => $firstname,
            ':lastname' => $lastname,
            ':role' => $roleId,
            ':company' => $companyId,
            ':id' => $id
        ]);
    }

    public function deleteUser(int $id)
    {
        $stmt = $this->database->connect()->prepare("
            DELETE FROM users WHERE id = :id
        ");
        $stmt->execute([':id' => $id]);
    }

    public function getById(int $id)
    {
        $stmt = $this->database->connect()->prepare("
            SELECT 
                id,
                email,
                firstname,
                lastname,
                role_id,
                company_id
            FROM users
            WHERE id = :id
        ");

        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }




}
