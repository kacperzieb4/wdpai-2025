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
        $templatePath = 'public/views/'. $template.'.html';
        $templatePath404 = 'public/views/404.html';

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
            http_response_code(403);
            include 'public/views/403.html';
            exit();
        }
    }

}
