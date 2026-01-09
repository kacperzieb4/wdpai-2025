<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/AssignmentRepository.php';
require_once __DIR__ . '/../repository/CommentRepository.php';

class FileController extends AppController {

    private $assignmentRepo;
    private $commentRepo;

    public function __construct()
    {
        parent::__construct();
        $this->assignmentRepo = new AssignmentRepository();
        $this->commentRepo = new CommentRepository();
    }

    public function show($id)
    {
        $this->requireLogin();

        if (!$id) {
            header("Location: /dashboard");
            exit();
        }

        $assignment = $this->assignmentRepo->getById($id);
        $comments = $this->commentRepo->getByAssignmentId($id);

        require 'public/views/file.html';
    }

    public function addComment()
    {
        $this->requireLogin();

        $assignmentId = $_POST['assignment_id'];
        $content = $_POST['content'];

        $this->commentRepo->addComment(
            $assignmentId,
            $_SESSION['user_id'],
            $content
        );

        header("Location: /file/$assignmentId");
        exit();
    }
}
