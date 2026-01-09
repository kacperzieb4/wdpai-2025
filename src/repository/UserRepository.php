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
}
