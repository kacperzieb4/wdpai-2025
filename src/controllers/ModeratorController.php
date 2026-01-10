<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/AssignmentRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class ModeratorController extends AppController {

    private $assignmentRepo;
    private $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->assignmentRepo = new AssignmentRepository();
        $this->userRepo = new UserRepository();
    }

    public function index()
    {
        $this->requireLogin();
        $this->requireRole('MODERATOR');

        $users = $this->userRepo->getAllUsers();

        require 'public/views/moderator.html';
    }

    public function createAssignment()
    {
        $this->requireLogin();
        $this->requireRole('MODERATOR');

        $title = $_POST['title'];
        $description = $_POST['description'];
        $videoPath = 'public/videos/' . $_FILES['video']['name'];

        move_uploaded_file($_FILES['video']['tmp_name'], $videoPath);

        $assignmentId = $this->assignmentRepo->createAssignment(
            $title,
            $description,
            $videoPath
        );

        foreach ($_POST['users'] as $userId) {
            $this->assignmentRepo->assignUser($assignmentId, $userId);
        }

        header("Location: /moderator");
        exit();
    }
}
