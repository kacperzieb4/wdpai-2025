<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/UserController.php';
require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/CardsController.php';


class Routing {

    public static $routes = [
        "login" => [
            "controller" => "SecurityController",
            "action" => "login"
        ],
        "register" => [
            "controller" => "SecurityController",
            "action" => "register"
        ],
        "dashboard" => [
            "controller" => "DashboardController",
            "action" => "index"
        ]
    ];

    public static function run(string $path) {
        $segments = explode('/', trim($path, '/'));

        // Strona główna
        if (empty($segments[0])) {
            $controller = new DashboardController();
            $controller->index();
            return;
        }

        switch ($segments[0]) {

            case 'login':
            case 'register':
                $controller = self::$routes[$segments[0]]["controller"];
                $action = self::$routes[$segments[0]]["action"];
                $ctrl = new $controller();
                $ctrl->$action();
                break;

            case 'user':
                if (isset($segments[1])) {
                    $id = $segments[1];
                    $ctrl = new UserController();
                    $ctrl->show($id);
                } else {
                    echo "Brak ID!";
                }
                break;

            case 'dashboard':
                $ctrl = new DashboardController();
                $ctrl->index();
                break;
            
            case 'logout':
                $ctrl = new SecurityController();
                $ctrl->logout();
                break;

            case 'search-cards':
                $ctrl = new CardsController();
                $ctrl->search();
                break;

            default:
                include 'public/views/404.html';
                break;
        }
    }
}
