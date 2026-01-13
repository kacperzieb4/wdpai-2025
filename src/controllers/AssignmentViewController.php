<?php

require_once 'src/repository/AssignmentViewRepository.php';

class AssignmentViewController
{
    private $repo;

    public function __construct()
    {
        $this->repo = new AssignmentViewRepository();
    }

    public function show($id)
    {
        if (!$id) {
            header("Location: /dashboard");
            exit();
        }

        $assignment = $this->repo->getAssignment($id);
        $comments = $this->repo->getComments($id);

        $this->render('assignment-view', [
            'assignment' => $assignment,
            'comments' => $comments
        ]);
    }

    public function addComment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->repo->addComment(
                $_POST['assignment_id'],
                $_SESSION['user_id'],
                $_POST['content']
            );

            header("Location: /assignment/" . $_POST['assignment_id']);
            exit();
        }
    }

    private function render(string $view, array $params = [])
    {
        extract($params);
        include "public/views/$view.html";
    }
}
