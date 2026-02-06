<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/UserController.php';
require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/CardsController.php';
require_once 'src/controllers/FileController.php';
require_once 'src/controllers/ModeratorController.php';
require_once 'src/controllers/AdminController.php';
require_once 'src/controllers/RegisterController.php';
require_once 'src/controllers/CompanyController.php';
require_once 'src/controllers/AssignmentController.php';
require_once 'src/controllers/AssignmentViewController.php';




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
        ],
        "create-user" => [
            "controller" => "UserController",
            "action" => "create"
        ],
        "companies" => [
            "controller" => "CompanyController",
            "action" => "index"
        ],
        "create-company" => [
            "controller" => "CompanyController",
            "action" => "create"
        ],
        "assign-user" => [
            "controller" => "CompanyController",
            "action" => "assignUser"
        ],
        "assignments" => [
            "controller" => "AssignmentController",
            "action" => "index"
        ],
        "create-assignment" => [
            "controller" => "AssignmentController",
            "action" => "create"
        ],
        "assign-assignment" => [
            "controller" => "AssignmentController",
            "action" => "assignUser"
        ],
        "assignment" => [
            "controller" => "AssignmentViewController",
            "action" => "show"
        ],
        "add-comment" => [
            "controller" => "AssignmentViewController",
            "action" => "addComment"
        ],





    ];

    public static function run(string $path) {
        $segments = explode('/', trim($path, '/'));

        // Strona główna
        if (empty($segments[0])) {
            require 'public/views/home.html';
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
            
            case 'create-user':
                $ctrl = new UserController();
                $ctrl->create();
                break;

            case 'companies':
            $ctrl = new CompanyController();
            $ctrl->index();
            break;

        case 'create-company':
            $ctrl = new CompanyController();
            $ctrl->create();
            break;

        case 'assignments':
            $ctrl = new AssignmentController();
            $ctrl->index();
            break;

        case 'create-assignment':
            $ctrl = new AssignmentController();
            $ctrl->create();
            break;

        case 'assign-assignment':
            $ctrl = new AssignmentController();
            $ctrl->assignUser();
            break;

        case 'assignment':
            $ctrl = new AssignmentViewController();
            $ctrl->show($segments[1] ?? null);
            break;

        case 'add-comment':
            $ctrl = new AssignmentViewController();
            $ctrl->addComment();
            break;




            case 'file':
                if ($segments[1] === 'comment') {
                    $ctrl = new FileController();
                    $ctrl->addComment();
                } else {
                    $ctrl = new FileController();
                    $ctrl->show($segments[1] ?? null);
                }
                break;

            case 'admin':
                $ctrl = new AdminController();
                $ctrl->index();
                break;

            case 'moderator':
                $ctrl = new ModeratorController();
                $ctrl->index();
                break;

            case 'moderator-create':
                $ctrl = new ModeratorController();
                $ctrl->createAssignment();
                break;
            
            case 'moderator-create-company':
                $ctrl = new ModeratorController();
                $ctrl->createCompany();
                break;

            case 'moderator-create-user':
                $ctrl = new ModeratorController();
                $ctrl->createUser();
                break;

            case 'register':
                $ctrl = new RegisterController();
                $ctrl->index();
                break;

            case 'register-submit':
                $ctrl = new RegisterController();
                $ctrl->register();
                break;

            case 'edit-assignment':
                $ctrl = new AssignmentController();
                $ctrl->edit($segments[1] ?? null);
                break;

            case 'manage-users':
                $ctrl = new UserController();
                $ctrl->index();
                break;

            case 'edit-user':
                $ctrl = new UserController();
                $ctrl->edit($segments[1] ?? null);
                break;

            case 'delete-user':
                $ctrl = new UserController();
                $ctrl->delete($segments[1] ?? null);
                break;

            case 'manage-companies':
                $ctrl = new CompanyController();
                $ctrl->index();
                break;

            case 'create-company':
                $ctrl = new CompanyController();
                $ctrl->create();
                break;

            case 'edit-company':
                $ctrl = new CompanyController();
                $ctrl->edit($segments[1] ?? null);
                break;

            case 'delete-company':
                $ctrl = new CompanyController();
                $ctrl->delete($segments[1] ?? null);
                break;

            case 'delete-company-with-users':
                $ctrl = new CompanyController();
                $ctrl->deleteWithUsers($segments[1] ?? null);
                break;

            case 'add-comment':
                $ctrl = new AssignmentViewController();
                $ctrl->addComment();
                break;

            case 'delete-comment':
                $ctrl = new AssignmentViewController();
                $ctrl->deleteComment($segments[1] ?? null);
                break;

            case 'edit-comment':
                $ctrl = new AssignmentViewController();
                $ctrl->editComment($segments[1] ?? null);
                break;



            default:
                include 'public/views/404.html';
                break;
        }
    }
}
