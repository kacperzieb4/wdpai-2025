<?php

require_once 'Repository.php';

class UserRepository extends Repository
{
    public function getUserByEmail(string $email)
    {
<<<<<<< Updated upstream
        $stmt = $this->database->connect()->prepare(
            "SELECT u.*, r.name AS role 
            FROM users u
            JOIN roles r ON u.role_id = r.id
            WHERE email = :email"
        );

        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser(
        string $email, 
        string $hashedpassword, 
=======
        $stmt = $this->database->connect()->prepare("
            SELECT * FROM users WHERE email = :email
        ");

        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmailWithRole(string $email)
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

    public function activateUser(string $email, string $password)
    {
        $stmt = $this->database->connect()->prepare("
            UPDATE users
            SET password = :password,
                is_active = true,
                activation_code = NULL
            WHERE email = :email
        ");

        $stmt->execute([
            ':email' => $email,
            ':password' => $password
        ]);
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
>>>>>>> Stashed changes
        string $firstname,
        string $lastname,
        int $roleId,
        string $code
    ) {
        $stmt = $this->database->connect()->prepare("
            INSERT INTO users (email, firstname, lastname, role_id, activation_code, is_active)
            VALUES (:email, :firstname, :lastname, :role, :code, false)
        ");

        $stmt->execute([
            ':email' => $email,
            ':firstname' => $firstname,
            ':lastname' => $lastname,
            ':role' => $roleId,
            ':code' => $code
        ]);
    }
<<<<<<< Updated upstream
    public function getAllUsers()
    {
        $stmt = $this->database->connect()->prepare(
            "SELECT id, firstname, lastname, email FROM users"
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function createUserWithActivation($first, $last, $email, $companyId, $code)
    {
        $stmt = $this->database->connect()->prepare(
            "INSERT INTO users 
            (firstname, lastname, email, role_id, company_id, activation_code, is_active)
            VALUES (:f, :l, :e, 1, :c, :code, false)"
        );

        $stmt->execute([
            ':f' => $first,
            ':l' => $last,
            ':e' => $email,
            ':c' => $companyId,
            ':code' => $code
        ]);
    }
    public function activateUser($email, $code, $password)
    {
        $stmt = $this->database->connect()->prepare(
            "UPDATE users 
            SET password = :p, is_active = true, activation_code = NULL
            WHERE email = :e AND activation_code = :c"
        );

        $stmt->execute([
            ':p' => $password,
            ':e' => $email,
            ':c' => $code
        ]);

        return $stmt->rowCount() > 0;
=======

    public function getUsers(): array
    {
        $stmt = $this->database->connect()->prepare("
            SELECT u.*, r.name AS role
            FROM users u
            JOIN roles r ON u.role_id = r.id
            ORDER BY u.id
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
>>>>>>> Stashed changes
    }


}
