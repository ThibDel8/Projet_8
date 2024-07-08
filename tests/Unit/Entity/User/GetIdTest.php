<?php

namespace App\Tests\Unit\Entity\User;

use PHPUnit\Framework\TestCase;
use App\Entity\User;

class GetIdTest extends TestCase
{
    public function testGetId()
    {
        $user = new User();
        $user->setId(1);

        $this->assertEquals(1, $user->getId());
    }
}