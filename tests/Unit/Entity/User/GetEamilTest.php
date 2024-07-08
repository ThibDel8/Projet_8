<?php

namespace App\Tests\Unit\Entity\User;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class GetEmailTest extends TestCase
{
    public function testGetEmail()
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $this->assertEquals('test@example.com', $user->getEmail());
    }
}
