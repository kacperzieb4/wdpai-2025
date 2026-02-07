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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
                $this->error(403, 'CSRF detected', 'Invalid request.');
            }

            $email = $_POST['email'];
            $password = $_POST['password'];

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->render('login', [
                    'messages' => ['Invalid email format.']
                ]);
                return;
            }

            $user = $this->userRepository->getUserByEmail($email);

            if (
                !$user ||
                !password_verify($password, $user['password']) ||
                !$user['is_active']
            ) {
                $this->render('login', [
                    'messages' => [
                        'Incorrect email or password, or the account is not activated.'
                    ]
                ]);
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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
                $this->error(403, 'CSRF detected', 'Invalid request.');
            } 

            $email = $_POST['email'];
            $code = $_POST['code'];
            $pass1 = $_POST['password'];
            $pass2 = $_POST['password2'];
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->render('login', [
                    'messages' => ['Niepoprawny format email']
                ]);
                return;
            }

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
            
            try {
                $this->userRepository->activateUser($email, $hashed);
            } catch (Throwable $e) {
                error_log($e->getMessage());
                $this->error(
                    500,
                    'Internal Server Error',
                    'Account activation failed. Please try again later.'
                );
            }

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
