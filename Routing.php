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
        try {
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
                    if (!isset($segments[1])) {
                        self::error(400, 'Bad request', 'Missing user ID.');
                    }
                    (new UserController())->edit($segments[1]);
                    break;

                case 'delete-user':
                    if (!isset($segments[1])) {
                        self::error(400, 'Bad request', 'Missing user ID.');
                    }
                    (new UserController())->delete($segments[1]);
                    break;

                case 'user':
                    if (!isset($segments[1])) {
                        self::error(400, 'Bad request', 'Missing user ID.');
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
                    if (!isset($segments[1])) {
                        self::error(400, 'Bad request', 'Missing company ID.');
                    }
                    (new CompanyController())->edit($segments[1]);
                    break;

                case 'delete-company':
                    if (!isset($segments[1])) {
                        self::error(400, 'Bad request', 'Missing company ID.');
                    }
                    (new CompanyController())->delete($segments[1]);
                    break;

                case 'delete-company-with-users':
                    if (!isset($segments[1])) {
                        self::error(400, 'Bad request', 'Missing company ID.');
                    }
                    (new CompanyController())->deleteWithUsers($segments[1]);
                    break;

                case 'assignments':
                    (new AssignmentController())->index();
                    break;

                case 'create-assignment':
                    (new AssignmentController())->create();
                    break;

                case 'edit-assignment':
                    if (!isset($segments[1])) {
                        self::error(400, 'Bad request', 'Missing assignment ID.');
                    }
                    (new AssignmentController())->edit($segments[1]);
                    break;

                case 'assign-assignment':
                    (new AssignmentController())->assignUser();
                    break;

                case 'assignment':
                    if (!isset($segments[1])) {
                        self::error(400, 'Bad request', 'Missing assignment ID.');
                    }
                    (new AssignmentViewController())->show($segments[1]);
                    break;

                case 'add-comment':
                    (new AssignmentViewController())->addComment();
                    break;

                case 'edit-comment':
                    if (!isset($segments[1])) {
                        self::error(400, 'Bad request', 'Missing comment ID.');
                    }
                    (new AssignmentViewController())->editComment($segments[1]);
                    break;

                case 'delete-comment':
                    if (!isset($segments[1])) {
                        self::error(400, 'Bad request', 'Missing comment ID.');
                    }
                    (new AssignmentViewController())->deleteComment($segments[1]);
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

                case 'change-password':
                    (new UserController())->changePassword();
                    break;

                default:
                    self::error(
                        404,
                        'Page not found',
                        'The page you are looking for does not exist.'
                    );
            }

        } catch (Throwable $e) {
            error_log($e->getMessage());
            self::error(
                500,
                'Internal Server Error',
                'Unexpected server error.'
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
