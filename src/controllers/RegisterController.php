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
        require 'public/views/register.html';
    }

    public function register()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $code = $_POST['code'];

        if ($password !== $password2) {
            $messages[] = "Passwords do not match.";
            require 'public/views/register.html';
            return;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);

        $success = $this->userRepo->activateUser($email, $code, $hash);

        if (!$success) {
            $messages[] = "Invalid activation code or email.";
            require 'public/views/register.html';
            return;
        }

        header("Location: /login");
        exit();
    }
}
