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
            $this->render('403');
            return;
        }

        $roles = $this->roleRepository->getAll();
        $this->render('roles', ['roles' => $roles]);
    }
}
