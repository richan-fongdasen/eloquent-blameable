<?php

namespace RichanFongdasen\EloquentBlameableTest;

class HelperTests extends TestCase
{
    /** @test */
    public function it_returns_current_user_identifier_correctly1()
    {
        $this->assertNull(blameable_user());
    }

    /** @test */
    public function it_returns_current_user_identifier_correctly2()
    {
        $this->impersonateUser();

        $this->assertEquals($this->user->getKey(), blameable_user());
    }

    /** @test */
    public function it_returns_current_user_identifier_correctly3()
    {
        $this->impersonateOtherUser();

        $this->assertEquals($this->otherUser->getKey(), blameable_user());
    }
}