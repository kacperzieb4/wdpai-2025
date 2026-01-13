<?php

require_once 'src/repository/UserRepository.php';

class UserController
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }
    public function create()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] === 'USER') {
            header("Location: /dashboard");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $roleName = $_POST['role'];

            $code = bin2hex(random_bytes(4)); // np. A1B2C3D4

            $roleId = $this->userRepository->getRoleIdByName($roleName);

            $this->userRepository->createInactiveUser(
                $email,
                $firstname,
                $lastname,
                $roleId,
                $code
            );

            $this->render('create-user', [
                'messages' => [
                    'User created successfully!',
                    'Activation code: ' . $code
                ]
            ]);

            return;
        }

        $this->render('create-user');
    }


    private function render(string $view, array $params = [])
    {
        extract($params);
        include "public/views/$view.html";
    }


}
