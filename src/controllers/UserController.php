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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $companies = $this->companyRepository->getAll();
            $roles = ['ADMIN', 'MODERATOR', 'USER'];

            require_once 'public/views/create-user.php';
            return;
        }

        $email = trim($_POST['email']);
        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);

        if (!isset($_POST['role'])) {
            $error = 'Role is required';
            $companies = $this->companyRepository->getAll();
            $roles = ['ADMIN', 'MODERATOR', 'USER'];
            require_once 'public/views/create-user.php';
            return;
        }

        $roleName = $_POST['role'];
        $roleId = $this->userRepository->getRoleIdByName($roleName);

        if (!$roleId) {
            $error = 'Invalid role';
            $companies = $this->companyRepository->getAll();
            $roles = ['ADMIN', 'MODERATOR', 'USER'];
            require_once 'public/views/create-user.php';
            return;
        }

        if ($roleName === 'ADMIN' || $roleName === 'MODERATOR') {
            $companyId = 1; 
        } else {
            if (empty($_POST['company'])) {
                $error = 'Company is required';
                $companies = $this->companyRepository->getAll();
                $roles = ['ADMIN', 'MODERATOR', 'USER'];
                require_once 'public/views/create-user.php';
                return;
            }
            $companyId = (int) $_POST['company'];
        }

        if ($this->userRepository->getUserByEmail($email)) {
            $error = 'User with this email already exists';
            $companies = $this->companyRepository->getAll();
            $roles = ['ADMIN', 'MODERATOR', 'USER'];
            require_once 'public/views/create-user.php';
            return;
        }

       if ($_SESSION['user_role'] === 'MODERATOR' && $roleName === 'ADMIN') {
            $error = 'You are not allowed to create admin users.';
            $companies = $this->companyRepository->getAll();
            $roles = ['ADMIN', 'MODERATOR', 'USER'];
            require 'public/views/create-user.php';
            return;
        }

        $activationCode = bin2hex(random_bytes(16));

        $this->userRepository->createInactiveUser(
            $email,
            $firstname,
            $lastname,
            $roleId,
            $companyId,
            $activationCode
        );

        $successCode = $activationCode;
        $companies = $this->companyRepository->getAll();
        $roles = ['ADMIN', 'MODERATOR', 'USER'];

        require_once 'public/views/create-user.php';
    }

    public function index()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] === 'USER') {
            $this->error(
                403,
                'Access denied',
                'Only moderators and admins can manage users.'
            );
        }

        $users = $this->userRepository->getAllUsersWithCompany();
        $this->render('manage-users', ['users' => $users]);
    }

    public function edit(int $id)
    {
        $user = $this->userRepository->getById($id);
        if (!$user) {
            $this->error(
                404,
                'User not found',
                'The requested user does not exist.'
            );
        }

        $roles = $this->roleRepository->getAll();
        $companies = $this->companyRepository->getAll();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require 'public/views/edit-user.php';
            return;
        }

        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $roleId = (int) $_POST['role_id'];
        $companyId = $_POST['company_id'] ?? null;

        $roleName = null;
        foreach ($roles as $r) {
            if ($r['id'] == $roleId) {
                $roleName = $r['name'];
                break;
            }
        }

        if (!$roleName) {
            $error = 'Invalid role';
            require 'public/views/edit-user.php';
            return;
        }

        if ($_SESSION['user_role'] === 'MODERATOR' && $roleName === 'ADMIN') {
            $error = 'You are not allowed to assign ADMIN role';
            require 'public/views/edit-user.php';
            return;
        }

        if ($roleName === 'ADMIN' || $roleName === 'MODERATOR') {
            $companyId = 1;
        }

        if ($roleName === 'USER' && !$companyId) {
            $error = 'User must be assigned to a company';
            require 'public/views/edit-user.php';
            return;
        }

        $this->userRepository->updateUser(
            $id,
            $firstname,
            $lastname,
            $roleId,
            (int) $companyId
        );

        header('Location: /manage-users');
        exit;
    }

    public function delete($id)
    {
        if (!isset($_SESSION['user_id'], $_SESSION['user_role'])) {
            $this->error(
                403,
                'Access denied',
                'You are not allowed to access this resource.'
            );
        }

        $id = (int)$id;

        if ($id === (int)$_SESSION['user_id']) {
            $this->error(
                403,
                'Access denied',
                'You cannot delete your own account.'
            );
        }

        $userToDelete = $this->userRepository->getById($id);
        if (!$userToDelete) {
            $this->error(
                404,
                'User not found',
                'The requested user does not exist.'
            );
            return;
        }

        $currentRole = $_SESSION['user_role'];
        $targetRoleId = $userToDelete['role_id'];

        if ($currentRole === 'ADMIN') {
            $this->userRepository->deleteUser($id);
            header('Location: /manage-users');
            exit;
        }

        $targetRole = $this->roleRepository->getById($userToDelete['role_id'])['name'];
        if ($currentRole === 'MODERATOR' && $targetRole === 'USER') {
            $this->userRepository->deleteUser($id);
        }

        $this->error(
            403,
            'Access denied',
            'You cannot delete this user.'
        );
    }



}
