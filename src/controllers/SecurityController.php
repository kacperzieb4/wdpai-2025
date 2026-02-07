<?php

require_once 'src/repository/UserRepository.php';

class SecurityController
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userRepository->getUserByEmail($email);

            if (!$user) {
                $this->render('login', ['messages' => ['User not found']]);
                return;
            }

            if (!$user['is_active']) {
                $messages[] = "Account not activated yet.";
                include 'public/views/login.php';
                return;
            }


            if (!password_verify($password, $user['password'])) {
                $this->render('login', ['messages' => ['Wrong password']]);
                return;
            }

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_firstname'] = $user['firstname'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_company_id'] = $user['company_id'];

            $_SESSION['is_logged_in'] = true;

            header("Location: /dashboard");
            exit;
        }

        $this->render('login');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = $_POST['email'];
            $code = $_POST['code'];
            $pass1 = $_POST['password'];
            $pass2 = $_POST['password2'];

            if ($pass1 !== $pass2) {
                $this->render('register', ['messages' => ['Passwords do not match']]);
                return;
            }

            $user = $this->userRepository->getUserByEmail($email);

            if (!$user || $user['activation_code'] !== $code) {
                $this->render('register', ['messages' => ['Invalid activation data']]);
                return;
            }

            if ($user['is_active']) {
                $this->render('register', ['messages' => ['Account already active']]);
                return;
            }

            $hashed = password_hash($pass1, PASSWORD_DEFAULT);
            $this->userRepository->activateUser($email, $hashed);

            header("Location: /login");
            exit();
        }

        $this->render('register');
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header("Location: /login");
        exit;
    }


    private function render(string $view, array $params = [])
    {
        extract($params);
        include "public/views/$view.php";
    }
}
