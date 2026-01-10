<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/CompanyRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class ModeratorController extends AppController {

    private $companyRepo;
    private $userRepo;

    public function __construct()
    {
        parent::__construct();
        $this->companyRepo = new CompanyRepository();
        $this->userRepo = new UserRepository();
    }

    public function index()
    {
        $this->requireLogin();
        $this->requireRole('MODERATOR');

        $companies = $this->companyRepo->getAll();

        require 'public/views/moderator.html';
    }

    public function createCompany()
    {
        $this->requireLogin();
        $this->requireRole('MODERATOR');

        $name = $_POST['company_name'];
        $this->companyRepo->create($name);

        header("Location: /moderator");
        exit();
    }

    public function createUser()
    {
        $this->requireLogin();
        $this->requireRole('MODERATOR');

        $code = bin2hex(random_bytes(16));

        $this->userRepo->createUserWithActivation(
            $_POST['firstname'],
            $_POST['lastname'],
            $_POST['email'],
            $_POST['company_id'],
            $code
        );

        // MAIL (na razie prosty)
        mail(
            $_POST['email'],
            "FINCH Activation",
            "Your activation code: $code"
        );

        header("Location: /moderator");
        exit();
    }
}
