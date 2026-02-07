<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/RoleRepository.php';

class RoleController extends AppController
{
    private RoleRepository $roleRepository;

    public function __construct()
    {
        parent::__construct();
        $this->roleRepository = new RoleRepository();
    }

    public function index()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] === 'USER') {
            $this->error(
                403,
                'Access denied',
                'You are not allowed to access this resource.'
            );
        }

        $roles = $this->roleRepository->getAll();
        $this->render('roles', ['roles' => $roles]);
    }
}
