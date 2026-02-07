<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/repository/UserRepository.php';
require_once __DIR__ . '/../src/Database.php';

class UserRepositoryTest extends TestCase
{
    public function testGetUserByEmailReturnsUser(): void
    {
        $repo = new UserRepository();
        $user = $repo->getUserByEmail('admin@finch.pl');

        $this->assertIsArray($user);
        $this->assertEquals('admin@finch.pl', $user['email']);
        $this->assertTrue($user['is_active']);
    }
}
