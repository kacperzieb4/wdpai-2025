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
        $this->requireLogin();

        if ($_SESSION['user_role'] === 'USER') {
            $this->error(
                403,
                'Access denied',
                'You are not allowed to create users.'
            );
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $companies = $this->companyRepository->getAll();
            $roles = ['ADMIN', 'MODERATOR', 'USER'];
            require 'public/views/create-user.php';
            return;
        }

        $email     = trim($_POST['email']);
        $firstname = trim($_POST['firstname']);
        $lastname  = trim($_POST['lastname']);

        if (empty($_POST['role'])) {
            $error = 'Role is required';
            $companies = $this->companyRepository->getAll();
            $roles = ['ADMIN', 'MODERATOR', 'USER'];
            require 'public/views/create-user.php';
            return;
        }

        $roleName = $_POST['role'];
        $roleId   = $this->userRepository->getRoleIdByName($roleName);

        if (!$roleId) {
            $error = 'Invalid role';
            $companies = $this->companyRepository->getAll();
            $roles = ['ADMIN', 'MODERATOR', 'USER'];
            require 'public/views/create-user.php';
            return;
        }

        if ($_SESSION['user_role'] === 'MODERATOR' && $roleName === 'ADMIN') {
            $error = 'You are not allowed to create admin users.';
            $companies = $this->companyRepository->getAll();
            $roles = ['ADMIN', 'MODERATOR', 'USER'];
            require 'public/views/create-user.php';
            return;
        }

        if ($roleName === 'ADMIN' || $roleName === 'MODERATOR') {
            $companyId = 1;
        } else {
            if (empty($_POST['company'])) {
                $error = 'Company is required';
                $companies = $this->companyRepository->getAll();
                $roles = ['ADMIN', 'MODERATOR', 'USER'];
                require 'public/views/create-user.php';
                return;
            }
            $companyId = (int) $_POST['company'];
        }

        if ($this->userRepository->getUserByEmail($email)) {
            $error = 'User with this email already exists';
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

        require 'public/views/create-user.php';
    }

    public function index()
    {
        $this->requireLogin();

        if ($_SESSION['user_role'] === 'USER') {
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
        $this->requireLogin();

        if ($_SESSION['user_role'] === 'USER') {
            $this->error(403, 'Access denied', 'You cannot edit users.');
        }

        $user = $this->userRepository->getById($id);
        if (!$user) {
            $this->error(404, 'User not found', 'The requested user does not exist.');
        }

        $roles     = $this->roleRepository->getAll();
        $companies = $this->companyRepository->getAll();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require 'public/views/edit-user.php';
            return;
        }

        $firstname = trim($_POST['firstname']);
        $lastname  = trim($_POST['lastname']);
        $roleId    = (int) $_POST['role_id'];
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
            (int)$companyId
        );

        header('Location: /manage-users');
        exit;
    }

    public function delete($id)
    {
        $this->requireLogin();

        if ($_SESSION['user_role'] === 'USER') {
            $this->error(403, 'Access denied', 'You cannot delete users.');
        }

        $id = (int)$id;

        if ($id === (int)$_SESSION['user_id']) {
            $this->error(403, 'Access denied', 'You cannot delete your own account.');
        }

        $userToDelete = $this->userRepository->getById($id);
        if (!$userToDelete) {
            $this->error(404, 'User not found', 'The requested user does not exist.');
        }

        $targetRole = $this->roleRepository
            ->getById($userToDelete['role_id'])['name'];

        if (
            $_SESSION['user_role'] === 'ADMIN' ||
            ($_SESSION['user_role'] === 'MODERATOR' && $targetRole === 'USER')
        ) {
            $this->userRepository->deleteUser($id);
            header('Location: /manage-users');
            exit;
        }

        $this->error(403, 'Access denied', 'You cannot delete this user.');
    }

    public function changePassword()
    {
        $this->requireLogin();

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword     = $_POST['new_password'] ?? '';
            $repeatPassword  = $_POST['new_password_repeat'] ?? '';

            if ($newPassword !== $repeatPassword) {
                $error = 'New passwords do not match.';
            } elseif (strlen($newPassword) < 6) {
                $error = 'Password must be at least 6 characters long.';
            } else {
                $db = Database::getInstance()->connect();

                $stmt = $db->prepare('SELECT password FROM users WHERE id = :id');
                $stmt->execute(['id' => $_SESSION['user_id']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user || !password_verify($currentPassword, $user['password'])) {
                    $error = 'Current password is incorrect.';
                } else {
                    $hash = password_hash($newPassword, PASSWORD_BCRYPT);

                    $update = $db->prepare(
                        'UPDATE users SET password = :password WHERE id = :id'
                    );
                    $update->execute([
                        'password' => $hash,
                        'id' => $_SESSION['user_id']
                    ]);

                    $success = 'Password changed successfully.';
                }
            }
        }

        require 'public/views/change-password.php';
    }
}
