<?php

require_once 'Repository.php';

class UserRepository extends Repository
{

    public function getUsers(): ?array
{
    $stmt = $this->database->connect()->prepare('SELECT * FROM users');
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}