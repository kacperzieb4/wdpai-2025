<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/UserController.php';

class Routing {

    public static $routes = [
        "login" => [
            "controller" => "SecurityController",
            "action" => "login"
        ],
        "register" => [
            "controller" => "SecurityController",
            "action" => "register"
        ]
    ];

    public static function run(string $path) {
        $segments = explode('/', trim($path, '/'));

        if (empty($segments[0])) {
            include 'public/views/dashboard.html';
            echo "<h2>Dashboard</h2>";
            return;
        }

        switch ($segments[0]) {
            case 'login':
            case 'register':
                $controller = Routing::$routes[$segments[0]]["controller"];
                $action = Routing::$routes[$segments[0]]["action"];
                $controllerObj = new $controller();
                $controllerObj->$action();
                break;

            case 'user': // dynamiczna ścieżka np. /user/123
                if (isset($segments[1])) {
                    $id = $segments[1];
                    // Wywołanie kontrolera użytkownika z ID
                    $controllerObj = new UserController();
                    $controllerObj->show($id);
                } else {
                    echo "Brak ID!";
                }
                break;

            default:
                include 'public/views/404.html';
                echo "<h2>404 - Nie znaleziono strony</h2>";
                break;
        }
    }
}

$path = $_SERVER['REQUEST_URI'];
Routing::run($path);
