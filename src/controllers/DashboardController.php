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

        $assignments = $this->assignmentRepo
            ->getUserAssignments($_SESSION['user_id']);

        $this->render('dashboard', [
            'assignments' => $assignments
        ]);
    }
}
