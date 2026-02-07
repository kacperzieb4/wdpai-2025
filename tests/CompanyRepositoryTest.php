<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/repository/CompanyRepository.php';
require_once __DIR__ . '/../Database.php';

class CompanyRepositoryTest extends TestCase
{
    public function testGetAllReturnsCompanies(): void
    {
        $repo = new CompanyRepository();
        $companies = $repo->getAll();

        $this->assertIsArray($companies);
        $this->assertNotEmpty($companies);

        $this->assertArrayHasKey('name', $companies[0]);
        $this->assertArrayHasKey('is_protected', $companies[0]);
    }
}
