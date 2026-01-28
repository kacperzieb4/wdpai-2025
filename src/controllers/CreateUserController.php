<?php

require_once 'Database.php';

class CreateUserController
{
    /* 
     * Router może wołać różne metody.
     * Każda z nich przekazuje do wspólnej logiki.
     */

    public function index()
    {
        $this->handle();
    }

    public function create()
    {
        $this->handle();
    }

    public function show()
    {
        $this->handle();
    }

    private function handle()
    {
        $db = new Database();
        $conn = $db->connect();

        // ROLE – MUSZĄ BYĆ
        $roles = $conn
            ->query("SELECT id, name FROM roles ORDER BY name")
            ->fetchAll(PDO::FETCH_ASSOC);

        // FIRMY – MUSZĄ BYĆ
        $companies = $conn
            ->query("SELECT id, name FROM companies ORDER BY name")
            ->fetchAll(PDO::FETCH_ASSOC);

        $successMessage = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $activationCode = bin2hex(random_bytes(4));

            $stmt = $conn->prepare("
                INSERT INTO users (
                    email, firstname, lastname, role_id, company_id, activation_code, is_active
                ) VALUES (
                    :email, :firstname, :lastname, :role_id, :company_id, :activation_code, false
                )
            ");

            $stmt->execute([
                'email' => $_POST['email'],
                'firstname' => $_POST['firstname'] ?: null,
                'lastname' => $_POST['lastname'] ?: null,
                'role_id' => $_POST['role_id'],
                'company_id' => $_POST['company_id'] ?: null,
                'activation_code' => $activationCode
            ]);

            $successMessage =
                "User created successfully. Activation code: {$activationCode}";
        }

        require 'public/views/create-user.html';
    }
}
