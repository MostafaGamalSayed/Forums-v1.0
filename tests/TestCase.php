<?php

namespace Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    protected function setUp()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    protected function signIn(User $user = null)
    {
        $user = $user?: create_factory('App\User');

        $this->actingAs($user);

        return $this;
    }
}
