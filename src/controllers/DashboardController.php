<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/AssignmentRepository.php';

class DashboardController extends AppController
{
    private AssignmentRepository $assignmentRepository;

    public function __construct()
    {
        parent::__construct();
        $this->assignmentRepository = new AssignmentRepository();
    }

    public function index()
    {
        $this->requireLogin();

        if (
            $_SESSION['user_role'] === 'ADMIN'
            || $_SESSION['user_role'] === 'MODERATOR'
        ) {
            $assignments = $this->assignmentRepository->getAllWithCompany();
        }
        else {
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
