<?php

require_once 'src/repository/CompanyRepository.php';
require_once 'src/repository/UserRepository.php';

class CompanyController
{
    private $companyRepository;
    private $userRepository;

    public function __construct()
    {
        $this->companyRepository = new CompanyRepository();
        $this->userRepository = new UserRepository();
    }

    public function index()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] === 'USER') {
            header("Location: /dashboard");
            exit();
        }

        $companies = $this->companyRepository->getAll();
        $users = $this->userRepository->getAllUsers();

        $this->render('companies', [
            'companies' => $companies,
            'users' => $users
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $this->companyRepository->create($name);

            header("Location: /companies");
            exit();
        }
    }

    public function assignUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'];
            $companyId = $_POST['company_id'];

            $this->userRepository->assignUserToCompany($userId, $companyId);

            header("Location: /companies");
            exit();
        }
    }

    private function render(string $view, array $params = [])
    {
        extract($params);
        include "public/views/$view.html";
    }
}
