<?php

require_once 'AppController.php';

require_once __DIR__ . '/../repository/AssignmentRepository.php';
require_once __DIR__ . '/../repository/CommentRepository.php';

class AssignmentViewController extends AppController
{
    private AssignmentRepository $assignmentRepository;
    private CommentRepository $commentRepository;

    public function __construct()
    {
        $this->assignmentRepository = new AssignmentRepository();
        $this->commentRepository = new CommentRepository();
    }

    public function show($id)
    {
        $this->view($id);
    }


    public function view($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $assignment = $this->assignmentRepository->getAssignment((int)$id);
        if (!$assignment) {
            $this->error(
                404,
                'Assignment not found',
                'This assignment does not exist or was removed.'
            );
        }

        $comments = $this->commentRepository->getByAssignmentId((int)$id);

        require 'public/views/assignment-view.php';
    }

    public function addComment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /dashboard');
            exit;
        }

        $assignmentId = (int)$_POST['assignment_id'];
        $content = trim($_POST['content']);
        $timestamp = $_POST['video_timestamp'] !== '' ? (int)$_POST['video_timestamp'] : null;

        if ($content === '') {
            header('Location: /assignment/' . $assignmentId);
            exit;
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

            if ($content !== '') {
                $this->commentRepository->update((int)$commentId, $content);
            }

            header('Location: /assignment/' . $comment['assignment_id']);
            exit;
        }
    }
}
