<?php

class AppController {

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function isGet(): bool
    {
        return $_SERVER["REQUEST_METHOD"] === 'GET';
    }

    protected function isPost(): bool
    {
        return $_SERVER["REQUEST_METHOD"] === 'POST';
    }

    protected function requireLogin(): void
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
    }

    protected function render(string $template = null, array $variables = [])
    {
        $templatePath = 'public/views/'. $template.'.php';
        $templatePath404 = 'public/views/404.php';

        if (file_exists($templatePath)) {
            extract($variables);
            ob_start();
            include $templatePath;
            echo ob_get_clean();
        } else {
            include $templatePath404;
        }
    }

    protected function requireRole(string $role): void
    {
        if (empty($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
            $this->error(
                403,
                'Access denied',
                'You do not have permission to access this section.'
            );
        }
    }

    protected function error(int $code, string $title,string $message): void {
        http_response_code($code);

        $code = $code;
        $title = $title;
        $message = $message;

        require 'public/views/error.php';
        exit;
    }

}
