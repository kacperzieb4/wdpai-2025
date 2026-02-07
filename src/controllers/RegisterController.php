<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class RegisterController extends AppController {

    private $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->userRepo = new UserRepository();
    }

    public function index()
    {
        require 'public/views/register.php';
    }

    public function register()
    {
        $email = $_POST['email'];
        $code = $_POST['code'];
        $pass1 = $_POST['password'];
        $pass2 = $_POST['password2'];

        if ($pass1 !== $pass2) {
            $messages[] = "Passwords do not match";
            include 'public/views/register.php';
            return;
        }

        $db = Database::getInstance()->connect();

        $stmt = $db->prepare("
            SELECT * FROM users 
            WHERE email = ? 
            AND activation_code = ?
            AND is_active = false
        ");
        $stmt->execute([$email, $code]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $messages[] = "Invalid activation code or account already active";
            include 'public/views/register.php';
            return;
        }

        $hash = password_hash($pass1, PASSWORD_DEFAULT);

        $stmt = $db->prepare("
            UPDATE users 
            SET password = ?, is_active = true, activation_code = NULL 
            WHERE id = ?
        ");
        $stmt->execute([$hash, $user['id']]);

        $messages[] = "Account activated! You can now log in.";
        include 'public/views/login.php';
    }

}
