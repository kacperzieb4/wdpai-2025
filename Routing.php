<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/RegisterController.php';
require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/UserController.php';
require_once 'src/controllers/CompanyController.php';
require_once 'src/controllers/AssignmentController.php';
require_once 'src/controllers/AssignmentViewController.php';
require_once 'src/controllers/ModeratorController.php';
require_once 'src/controllers/AdminController.php';

class Routing
{
    public static function run(string $path)
    {
        $segments = explode('/', trim($path, '/'));
        $route = $segments[0] ?? '';

        if ($route === '') {
            require 'public/views/home.php';
            return;
        }

        switch ($route) {

            case 'login':
                (new SecurityController())->login();
                break;

            case 'logout':
                (new SecurityController())->logout();
                break;

            case 'register':
                (new RegisterController())->index();
                break;

            case 'register-submit':
                (new RegisterController())->register();
                break;

            case 'dashboard':
                (new DashboardController())->index();
                break;

            case 'manage-users':
                (new UserController())->index();
                break;

            case 'create-user':
                (new UserController())->create();
                break;

            case 'edit-user':
                (new UserController())->edit($segments[1] ?? null);
                break;

            case 'delete-user':
                (new UserController())->delete($segments[1] ?? null);
                break;

            case 'user':
                if (!isset($segments[1])) {
                    echo 'Brak ID użytkownika';
                    break;
                }
                (new UserController())->show($segments[1]);
                break;

            case 'manage-companies':
                (new CompanyController())->index();
                break;

            case 'create-company':
                (new CompanyController())->create();
                break;

            case 'edit-company':
                (new CompanyController())->edit($segments[1] ?? null);
                break;

            case 'delete-company':
                (new CompanyController())->delete($segments[1] ?? null);
                break;

            case 'delete-company-with-users':
                (new CompanyController())->deleteWithUsers($segments[1] ?? null);
                break;

            case 'assignments':
                (new AssignmentController())->index();
                break;

            case 'create-assignment':
                (new AssignmentController())->create();
                break;

            case 'edit-assignment':
                (new AssignmentController())->edit($segments[1] ?? null);
                break;

            case 'assign-assignment':
                (new AssignmentController())->assignUser();
                break;

            case 'assignment':
                (new AssignmentViewController())->show($segments[1] ?? null);
                break;

            case 'add-comment':
                (new AssignmentViewController())->addComment();
                break;

            case 'edit-comment':
                (new AssignmentViewController())->editComment($segments[1] ?? null);
                break;

            case 'delete-comment':
                (new AssignmentViewController())->deleteComment($segments[1] ?? null);
                break;

            case 'moderator-create-company':
                (new ModeratorController())->createCompany();
                break;

            case 'moderator-create-user':
                (new ModeratorController())->createUser();
                break;

            case 'admin':
                (new AdminController())->index();
                break;

            default:
                self::error(
                    404,
                    'Page not found',
                    'The page you are looking for does not exist.'
                );
        }
    }
    private static function error(
        int $code,
        string $title,
        string $message
    ) {
        http_response_code($code);

        require 'public/views/error.php';
        exit;
    }

}
