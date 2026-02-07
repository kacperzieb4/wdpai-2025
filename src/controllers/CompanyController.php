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
            $this->error(
                403,
                'Access denied',
                'You are not allowed to access this resource.'
            );
        }
    }

    public function index()
    {
        $this->checkAccess();

        try {
            $companies = $this->companyRepository->getAll();
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $this->error(
                500,
                'Internal Server Error',
                'Unable to load companies.'
            );
        }

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
                    error_log($e->getMessage());
                    $this->error(
                        500,
                        'Internal Server Error',
                        'Could not create company.'
                    );
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
            $this->error(
                404,
                'Company not found',
                'The selected company does not exist.'
            );
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
                    error_log($e->getMessage());
                    $this->error(
                        500,
                        'Internal Server Error',
                        'Could not update company.'
                    );
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

        $company = $this->companyRepository->getById((int)$id);
        if (!$company) {
            $this->error(
                404,
                'Company not found',
                'The selected company does not exist.'
            );
        }

        $this->ensureNotProtected($company);

        try {
            $this->companyRepository->delete((int)$id);
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $this->error(
                500,
                'Internal Server Error',
                'Could not delete company.'
            );
        }
        header('Location: /manage-companies');
        exit;
    }


    public function deleteWithUsers($id)
    {
        $this->checkAccess();

        $company = $this->companyRepository->getById((int)$id);
        if (!$company) {
            $this->error(
                404,
                'Company not found',
                'The selected company does not exist.'
            );
        }

        $this->ensureNotProtected($company);

        try {
            $this->companyRepository->deleteWithUsers((int)$id);
            header('Location: /manage-companies');
            exit;
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $this->error(
                500,
                'Internal Server Error',
                'Could not delete company with users.'
            );
        }
    }


    private function ensureNotProtected(array $company)
    {
        if (!empty($company['is_protected'])) {
            $this->error(
                403,
                'Access denied',
                'You are not allowed to manage companies.'
            );
        }
    }

}