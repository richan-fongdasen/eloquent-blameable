<?php

namespace RichanFongdasen\EloquentBlameableTest;

use RichanFongdasen\EloquentBlameableTest\Models\Article;
use RichanFongdasen\EloquentBlameableTest\Models\Post;
use RichanFongdasen\EloquentBlameableTest\Models\PostWithoutAttributes;
use RichanFongdasen\EloquentBlameable\BlameableService;

class HelperTests extends TestCase
{
    /** @test */
    public function it_returns_null_as_current_user_identifier()
    {
        $this->assertNull(blameable_user(new Post));
    }

    /** @test */
    public function it_returns_current_user_identifier_when_calling_as_user()
    {
        $this->impersonateUser();

        $this->assertEquals($this->user->getKey(), blameable_user(new Post));
    }

    /** @test */
    public function it_returns_current_user_identifier_when_calling_as_other_user()
    {
        $this->impersonateOtherUser();

        $this->assertEquals($this->otherUser->getKey(), blameable_user(new Post));
    }

    /** @test */
    public function it_returns_null_as_current_user_identifier_when_current_user_is_other_class()
    {
        $this->impersonateUser();

        $this->assertNull(blameable_user(new PostWithoutAttributes));
    }

    /** @test */
    public function it_returns_current_impersonated_admin_id()
    {
        $this->impersonateAdmin();

        $this->assertEquals($this->admin->getKey(), blameable_user(new Article));
    }

    /** @test */
    public function it_returns_null_as_current_user_for_the_unauthenticated_user()
    {
        $this->assertNull(blameable_user(new Article));
    }
}
