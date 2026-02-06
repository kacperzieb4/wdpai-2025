<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/CompanyRepository.php';

class CompanyController extends AppController
{
    private CompanyRepository $companyRepository;

    public function __construct()
    {
        parent::__construct();
        $this->companyRepository = new CompanyRepository();
    }

    private function checkAccess()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] === 'USER') {
            $this->render('403');
            exit;
        }
    }

    public function index()
    {
        $this->checkAccess();

        $companies = $this->companyRepository->getAll();
        $this->render('manage-companies', ['companies' => $companies]);
    }

    public function create()
    {
        $this->checkAccess();

        $errorMessage = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->companyRepository->create($_POST['name']);
                header('Location: /manage-companies');
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() === '23505') {
                    $errorMessage = 'Company with this name already exists.';
                } else {
                    $errorMessage = 'Unexpected error occurred.';
                }
            }
        }

        $this->render('create-company', [
            'errorMessage' => $errorMessage
        ]);
    }

    public function edit($id)
    {
        $this->checkAccess();

        $company = $this->companyRepository->getById((int)$id);
        if (!$company) {
            $this->render('404');
            return;
        }

        $errorMessage = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->companyRepository->update((int)$id, $_POST['name']);
                header('Location: /manage-companies');
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() === '23505') {
                    $errorMessage = 'Company with this name already exists.';
                } else {
                    $errorMessage = 'Unexpected error occurred.';
                }
            }
        }

        $this->render('edit-company', [
            'company' => $company,
            'errorMessage' => $errorMessage
        ]);
    }

    public function delete($id)
    {
        $this->checkAccess();

        $this->companyRepository->delete((int)$id);
        header('Location: /manage-companies');
        exit;
    }

    public function deleteWithUsers($id)
    {
        $this->checkAccess();

        $this->companyRepository->deleteWithUsers((int)$id);

        header('Location: /manage-companies');
        exit;
    }

    private function ensureNotProtected(array $company)
    {
        if (!empty($company['is_protected'])) {
            $this->render('403');
            exit;
        }
    }

}
