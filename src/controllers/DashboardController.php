<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/AssignmentRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class DashboardController extends AppController
{
    private AssignmentRepository $assignmentRepository;
    private UserRepository $userRepository;

   public function __construct()
    {
        parent::__construct();
        $this->assignmentRepository = new AssignmentRepository();
        $this->userRepository = new UserRepository();
    }

    public function index()
    {
        $this->requireLogin();
        $user = $this->userRepository->getUserById($_SESSION['user_id']);

        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_company_id'] = $user['company_id'];

        if (
            $_SESSION['user_role'] === 'ADMIN'
            || $_SESSION['user_role'] === 'MODERATOR'
        ) {
            $assignments = $this->assignmentRepository->getAllWithCompany();
        } else {
            if (empty($_SESSION['user_company_id'])) {
                $assignments = [];
            } else {
                $assignments = $this->assignmentRepository->getByCompanyId(
                    $_SESSION['user_company_id']
                );
            }
        }

        require_once __DIR__ . '/../../public/views/dashboard.php';
    }
}
