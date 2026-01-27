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

        $assignments = $this->assignmentRepository->getAll();
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
        // opcjonalnie: blokada dla USER
        if ($_SESSION['user_role'] === 'USER') {
            include 'public/views/403.html';
            return;
        }

        // ===== GET – pokaż formularz =====
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $companies = $this->companyRepository->getAll();

            $this->render('create-assignment', [
                'companies' => $companies
            ]);
            return;
        }

        // ===== POST – zapisz assignment =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $title = $_POST['title'];
            $desc = $_POST['description'];
            $company = $_POST['company_id'];

            $video = $_FILES['video'];

            if ($video['error'] !== 0) {
                die("Upload error");
            }

            $filename = uniqid() . '_' . basename($video['name']);
            $target = "public/uploads/" . $filename;

            move_uploaded_file($video['tmp_name'], $target);

            $this->assignmentRepository->create(
                $title,
                $desc,
                $target,
                $company
            );

            header("Location: /assignments");
            exit();
        }
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
        include "public/views/$view.html";
    }

    public function edit($id)
    {
        $assignment = $this->assignmentRepository->find($id);
        $companies = $this->companyRepository->getAll();

        $this->render('edit-assignment', [
            'assignment' => $assignment,
            'companies' => $companies
        ]);
    }

}
