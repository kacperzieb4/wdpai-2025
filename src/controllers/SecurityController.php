<?php

require_once 'AppController.php';

class SecurityController extends AppController {

    public function login() {

        if(!$this->isPost()) {
            return $this->render("login");
        }

         $email = $_POST["email"] ?? '';
         $password = $_POST["password"] ?? '';

        return $this->render("dashboard", ["cards" => " "]);

        $url ="http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/dashboard");
    }

    public function register() {
        return $this->render("register");
    }
}

