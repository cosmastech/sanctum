<?php

namespace Laravel\Sanctum\Tests\Unit;

use Laravel\Sanctum\PersonalAccessToken;
use PHPUnit\Framework\TestCase;

class PersonalAccessTokenTest extends TestCase
{
    public function test_can_determine_what_it_can_and_cant_do()
    {
        $token = new PersonalAccessToken;

        $token->abilities = [];

        $this->assertFalse($token->can('foo'));

        $token->abilities = ['foo'];

        $this->assertTrue($token->can('foo'));
        $this->assertFalse($token->can('bar'));
        $this->assertTrue($token->cant('bar'));
        $this->assertFalse($token->cant('foo'));

        $token->abilities = ['foo', '*'];

        $this->assertTrue($token->can('foo'));
        $this->assertTrue($token->can('bar'));
    }

    public function test_extended_token_can_merge_parent_casts()
    {
        $extendedToken = new ExtendedPersonalAccessToken();

        $this->assertArrayHasKey('is_impersonation', $extendedToken->getCasts());
        $this->assertEquals('bool', $extendedToken->getCasts()['is_impersonation']);
    }
}

class ExtendedPersonalAccessToken extends PersonalAccessToken
{
    protected function casts()
    {
        return parent::casts() + ['is_impersonation' => 'bool'];
    }
}
