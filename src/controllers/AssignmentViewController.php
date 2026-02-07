<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/AssignmentRepository.php';
require_once __DIR__ . '/../repository/CommentRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class AssignmentViewController extends AppController
{
    private AssignmentRepository $assignmentRepository;
    private CommentRepository $commentRepository;
    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->assignmentRepository = new AssignmentRepository();
        $this->commentRepository = new CommentRepository();
        $this->userRepository = new UserRepository();
    }

    public function show($id)
    {
        $this->view($id);
    }

    public function view($id)
    {
        $this->requireLogin();

        $user = $this->userRepository->getUserById($_SESSION['user_id']);
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_company_id'] = $user['company_id'];

        $assignment = $this->assignmentRepository->getAssignment((int)$id);
        if (!$assignment) {
            $this->error(
                404,
                'Assignment not found',
                'This assignment does not exist or was removed.'
            );
        }

        if (
            $_SESSION['user_role'] === 'USER'
            && $assignment['company_id'] !== $_SESSION['user_company_id']
        ) {
            $this->error(
                403,
                'Access denied',
                'You are not allowed to view this assignment.'
            );
        }

        $comments = $this->commentRepository->getByAssignmentId((int)$id);

        require 'public/views/assignment-view.php';
    }

    public function addComment()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /dashboard');
            exit;
        }

        $assignmentId = (int)$_POST['assignment_id'];

        $assignment = $this->assignmentRepository->getAssignment($assignmentId);
        if (!$assignment) {
            $this->error(404, 'Not found', 'Assignment does not exist.');
        }

        if (
            $_SESSION['user_role'] === 'USER'
            && $assignment['company_id'] !== $_SESSION['user_company_id']
        ) {
            $this->error(
                403,
                'Access denied',
                'You are not allowed to comment on this assignment.'
            );
        }

        $content = trim($_POST['content']);
        $timestamp = $_POST['video_timestamp'] !== ''
            ? (int)$_POST['video_timestamp']
            : null;

        if ($content === '') {
            header('Location: /assignment/' . $assignmentId);
            exit;
        }

        if (strlen($content) > 300) {
            $this->error(400, 'Invalid input', 'Comment too long');
        }

        $this->commentRepository->addComment(
            $assignmentId,
            $_SESSION['user_id'],
            $content,
            $timestamp
        );

        header('Location: /assignment/' . $assignmentId);
        exit;
    }

    public function deleteComment($commentId)
    {
        $this->requireLogin();

        $comment = $this->commentRepository->getById((int)$commentId);
        if (!$comment) {
            header('Location: /dashboard');
            exit;
        }

        $canDelete =
            $comment['user_id'] == $_SESSION['user_id']
            || in_array($_SESSION['user_role'], ['ADMIN', 'MODERATOR']);

        if (!$canDelete) {
            $this->error(
                403,
                'Access denied',
                'You are not allowed to delete this comment.'
            );
        }

        $this->commentRepository->delete((int)$commentId);

        header('Location: /assignment/' . $comment['assignment_id']);
        exit;
    }

    public function editComment($commentId)
    {
        $this->requireLogin();

        $comment = $this->commentRepository->getById((int)$commentId);
        if (!$comment) {
            header('Location: /dashboard');
            exit;
        }

        $canEdit =
            $comment['user_id'] == $_SESSION['user_id']
            || in_array($_SESSION['user_role'], ['ADMIN', 'MODERATOR']);

        if (!$canEdit) {
            $this->error(
                403,
                'Access denied',
                'You are not allowed to edit this comment.'
            );
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = trim($_POST['content']);

            if (strlen($content) > 300) {
                $this->error(400, 'Invalid input', 'Comment too long');
            }

            if ($content !== '') {
                $this->commentRepository->update((int)$commentId, $content);
            }

            header('Location: /assignment/' . $comment['assignment_id']);
            exit;
        }
    }
}
