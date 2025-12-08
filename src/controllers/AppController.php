<?php

class AppController {

    public function __construct()
    {
        // Uruchamiamy sesję, jeśli jeszcze nie działa
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
        // Jeśli użytkownik NIE jest zalogowany → przekieruj na login
        if (empty($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
    }

    protected function render(string $template = null, array $variables = [])
    {
        $templatePath = 'public/views/'. $template.'.html';
        $templatePath404 = 'public/views/404.html';
        $output = "";
        
        if (file_exists($templatePath)) {
            extract($variables);

            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        } 
        else {
            ob_start();
            include $templatePath404;
            $output = ob_get_clean();
        }

        echo $output;
    }
}
