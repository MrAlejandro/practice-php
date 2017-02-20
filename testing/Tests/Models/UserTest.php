<?php

namespace Acme\Tests;

use Acme\Models;

class UserTest extends AcmeBaseIntegrationTest
{

    public function testGetTestimoinalsForUser()
    {
        $user = User::find(1);
        $testimonials = $user->testimonials();

        $actual = get_class($testimonials);
        $expected = "Illuminate\\Database\\Eloquent\\Relations\\HasMany";
        $this->assertEquals($actual, $expected);
    }
}
