<?php

namespace App\Tests\Unit\Entity\User;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class GetUsernameTest extends TestCase
{
    public function testGetUsername()
    {
        $user = new User();
        $user->setUsername('Test');

        $this->assertEquals('Test', $user->getUsername());
    }
}
