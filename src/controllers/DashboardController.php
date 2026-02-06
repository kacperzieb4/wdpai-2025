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
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        require_once __DIR__ . '/../repository/AssignmentRepository.php';

        $assignmentRepository = new AssignmentRepository();

        if ($_SESSION['user_role'] === 'USER') {
            $assignments = $assignmentRepository->getUserAssignments(
                $_SESSION['user_id']
            );
        } else {
            $assignments = $assignmentRepository->getAllWithCompany();
        }

        require_once __DIR__ . '/../../public/views/dashboard.html';
    }
}
