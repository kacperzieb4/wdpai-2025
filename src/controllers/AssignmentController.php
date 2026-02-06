<?php

require_once 'src/repository/AssignmentRepository.php';
require_once 'src/repository/UserRepository.php';
require_once 'src/repository/CompanyRepository.php';

class AssignmentController
{
    private $assignmentRepository;
    private $userRepository;
    private $companyRepository;

    public function __construct()
    {
        $this->assignmentRepository = new AssignmentRepository();
        $this->userRepository = new UserRepository();
        $this->companyRepository = new CompanyRepository();
    }

    public function index()
    {
        if ($_SESSION['user_role'] === 'USER') {
            header("Location: /dashboard");
            exit();
        }

        $assignments = $this->assignmentRepository->getAllWithCompany();
        $users = $this->userRepository->getAllUsers();
        $companies = $this->companyRepository->getAll();

        $this->render('assignments', [
            'assignments' => $assignments,
            'users' => $users,
            'companies' => $companies
        ]);
    }

    public function create()
    {
        if ($_SESSION['user_role'] === 'USER') {
            $this->render('403');
            return;
        }

        require_once __DIR__ . '/../repository/AssignmentRepository.php';
        require_once __DIR__ . '/../repository/CompanyRepository.php';

        $assignmentRepository = new AssignmentRepository();
        $companyRepository = new CompanyRepository();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $description = $_POST['description'] ?? null;
            $companyId = $_POST['company_id'];

            $originalName = basename($_FILES['video']['name']);
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);

            $uniqueCode = uniqid();

            $filename = $uniqueCode . '_' . $originalName;

            $videoPath = 'public/uploads/' . $filename;

            move_uploaded_file($_FILES['video']['tmp_name'], $videoPath);


            $assignmentRepository->create(
                $title,
                $description,
                $videoPath,
                (int)$companyId
            );

            header('Location: /assignments');
            exit;
        }

        $companies = $companyRepository->getAll();

        $this->render('create-assignment', [
            'companies' => $companies
        ]);
    }

    public function assignUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->assignmentRepository->assignToUser(
                $_POST['assignment_id'],
                $_POST['user_id']
            );

            header("Location: /assignments");
            exit();
        }
    }

    private function render(string $view, array $params = [])
    {
        extract($params);
        include "public/views/$view.php";
    }

    public function edit($id)
    {
        if ($_SESSION['user_role'] === 'USER') {
            $this->render('403');
            return;
        }

        require_once __DIR__ . '/../repository/AssignmentRepository.php';
        require_once __DIR__ . '/../repository/CompanyRepository.php';

        $assignmentRepository = new AssignmentRepository();
        $companyRepository = new CompanyRepository();

        $assignment = $assignmentRepository->getAssignment((int)$id);
        if (!$assignment) {
            $this->render('404');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $description = $_POST['description'] ?? null;
            $companyId = (int)$_POST['company_id'];

            $newVideoPath = null;

            if (!empty($_FILES['video']['name'])) {

                if (!empty($assignment['video_path'])) {
                    $oldFile = __DIR__ . '/../../' . $assignment['video_path'];
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }

                $originalName = basename($_FILES['video']['name']);
                $uniqueCode = uniqid();
                $filename = $uniqueCode . '_' . $originalName;
                $newVideoPath = 'public/uploads/' . $filename;

                move_uploaded_file(
                    $_FILES['video']['tmp_name'],
                    __DIR__ . '/../../' . $newVideoPath
                );
            }

            $assignmentRepository->update(
                (int)$id,
                $title,
                $description,
                $companyId,
                $newVideoPath
            );

            header('Location: /assignments');
            exit;
        }

        $companies = $companyRepository->getAll();

        $this->render('edit-assignment', [
            'assignment' => $assignment,
            'companies' => $companies
        ]);
    }

    public function delete($id)
    {
        if ($_SESSION['user_role'] === 'USER') {
            $this->render('403');
            return;
        }

        require_once __DIR__ . '/../repository/AssignmentRepository.php';

        $assignmentRepository = new AssignmentRepository();

        $assignment = $assignmentRepository->getAssignment((int)$id);
        if (!$assignment) {
            $this->render('404');
            return;
        }

        $filePath = __DIR__ . '/../../' . $assignment['video_path'];

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $assignmentRepository->delete((int)$id);

        header('Location: /assignments');
        exit;
    }

    public function deleteComment($commentId)
    {
        require_once __DIR__ . '/../repository/CommentRepository.php';

        $repo = new CommentRepository();
        $comment = $repo->getById((int)$commentId);

        if (!$comment) {
            header('Location: /dashboard');
            exit;
        }

        $canDelete =
            $comment['user_id'] == $_SESSION['user_id'] ||
            in_array($_SESSION['user_role'], ['ADMIN', 'MODERATOR']);

        if (!$canDelete) {
            $this->render('403');
            return;
        }

        $repo->delete((int)$commentId);

        header('Location: /assignment/' . $comment['assignment_id']);
        exit;
    }

    public function editComment($commentId)
    {
        require_once __DIR__ . '/../repository/CommentRepository.php';

        $repo = new CommentRepository();
        $comment = $repo->getById((int)$commentId);

        if (!$comment) {
            header('Location: /dashboard');
            exit;
        }

        $canEdit =
            $comment['user_id'] == $_SESSION['user_id'] ||
            in_array($_SESSION['user_role'], ['ADMIN', 'MODERATOR']);

        if (!$canEdit) {
            $this->render('403');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = trim($_POST['content']);

            if ($content !== '') {
                $repo->update((int)$commentId, $content);
            }

            header('Location: /assignment/' . $comment['assignment_id']);
            exit;
        }

        $this->render('edit-comment', [
            'comment' => $comment
        ]);
    }




}
