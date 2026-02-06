<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../repository/CompanyRepository.php';
require_once __DIR__ . '/../repository/RoleRepository.php';

class UserController extends AppController
{
    private UserRepository $userRepository;
    private CompanyRepository $companyRepository;
    private RoleRepository $roleRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->companyRepository = new CompanyRepository();
        $this->roleRepository = new RoleRepository();
    }

    public function create()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] === 'USER') {
            $this->render('403');
            return;
        }

        $activationCode = null;
        $createdEmail = null;
        $errorMessage = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (
                $_SESSION['user_role'] === 'MODERATOR'
                && $this->roleRepository->getNameById((int)$_POST['role_id']) === 'ADMIN'
            ) {
                $errorMessage = 'You are not allowed to create an ADMIN user.';
            } else {
                try {
                    $activationCode = bin2hex(random_bytes(16));
                    $createdEmail = $_POST['email'];

                    $this->userRepository->createInactiveUser(
                        $createdEmail,
                        $_POST['firstname'],
                        $_POST['lastname'],
                        (int)$_POST['role_id'],
                        $activationCode
                    );

                    if (!empty($_POST['company_id'])) {
                        $user = $this->userRepository->getUserByEmail($createdEmail);
                        $this->userRepository->assignUserToCompany(
                            (int)$user['id'],
                            (int)$_POST['company_id']
                        );
                    }

                } catch (PDOException $e) {

                    if ($e->getCode() === '23505') {
                        $errorMessage = 'User with this email already exists.';
                    } else {
                        $errorMessage = 'Unexpected error occurred. Please try again.';
                    }

                    $activationCode = null;
                    $createdEmail = null;
                }
            }
        }

        $roles = $this->roleRepository->getAll();
        $companies = $this->companyRepository->getAll();

        $this->render('create-user', [
            'roles' => $roles,
            'companies' => $companies,
            'activationCode' => $activationCode,
            'createdEmail' => $createdEmail,
            'errorMessage' => $errorMessage
        ]);
    }

    public function index()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] === 'USER') {
            $this->render('403');
            return;
        }

        $users = $this->userRepository->getAllUsersWithCompany();
        $this->render('manage-users', ['users' => $users]);
    }

    public function edit($id)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] === 'USER') {
            $this->render('403');
            return;
        }

        if (!$id) {
            $this->render('404');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userRepository->updateUser(
                $id,
                $_POST['firstname'],
                $_POST['lastname'],
                $_POST['role_id'],
                $_POST['company_id'] ?: null
            );

            header('Location: /manage-users');
            exit;
        }

        $user = $this->userRepository->getUserById($id);
        $roles = $this->roleRepository->getAll();
        $companies = $this->companyRepository->getAll();

        $this->render('edit-user', [
            'user' => $user,
            'roles' => $roles,
            'companies' => $companies
        ]);
    }

    public function delete($id)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'ADMIN') {
            $this->render('403');
            return;
        }

        if ((int)$id === (int)$_SESSION['user_id']) {
            header('Location: /manage-users');
            exit;
        }

        $this->userRepository->deleteUser($id);
        header('Location: /manage-users');
        exit;
    }

}
