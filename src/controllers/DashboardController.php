<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/AssignmentRepository.php';

class DashboardController extends AppController {

    private $assignmentRepo;

    public function __construct()
    {
        parent::__construct();
        $this->assignmentRepo = new AssignmentRepository();
    }

    public function index()
    {
        $this->requireLogin();

        if ($_SESSION['user_role'] === 'USER') {
            $assignments = $this->assignmentRepo
                ->getUserAssignments($_SESSION['user_id']);
        } else {
            $assignments = $this->assignmentRepo->getAll();
        }

        $this->render('dashboard', [
            'assignments' => $assignments
        ]);
    }

}
