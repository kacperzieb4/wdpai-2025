<?php

require_once 'AppController.php';

class AdminController extends AppController {

    public function index()
    {
        $this->requireLogin();
        $this->requireRole('ADMIN');

        require 'public/views/admin.php';
    }
}
