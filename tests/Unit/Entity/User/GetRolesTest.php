<?php

namespace App\Tests\Unit\Entity\User;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class GetRolesTest extends TestCase
{
    public function testGetRoles()
    {
        $user = new User();
        $user->setRoles(['ROLE_TEST']);

        $this->assertEquals(['ROLE_TEST'], $user->getRoles());
    }
}
