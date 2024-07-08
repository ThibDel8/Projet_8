<?php

namespace App\Tests\Unit\Entity\User;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class GetPasswordTest extends TestCase
{
    public function testGetPassword()
    {
        $hashedPassword = password_hash('password123', PASSWORD_DEFAULT);

        $user = new User();
        $user->setPassword($hashedPassword);

        $this->assertEquals($hashedPassword, $user->getPassword());
    }
}
