<?php

require_once 'Repository.php';

class UserRepository extends Repository
{
    public function getUsers(): ?array
    {
        $query = $this->database->connect()->prepare(
            "
                SELECT * FROM users;
            "
        );

        $query->execute();

        $users = $query->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }
    public function getUserByEmail(string $email)
    {
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
        string $firstname,
        string $lastname,
        string $bio = ' '
    ){
        $query = $this->database->connect()->prepare(
            "
                INSERT INTO users (firstname, lastname, email, password, bio) 
                VALUES (?, ?, ?, ?, ?);
            "
        );

    $query->execute([
            $firstname,
            $lastname,
            $email,
            $hashedpassword,
            $bio,
        ]);

    }
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
    }


}
