<?php

namespace RichanFongdasen\EloquentBlameableTest;

use RichanFongdasen\EloquentBlameableTest\Models\Post;
use RichanFongdasen\EloquentBlameableTest\Models\PostWithoutAttributes;

use RichanFongdasen\EloquentBlameable\BlameableService;

class HelperTests extends TestCase
{

    /**
     * The Eloquent model to test the helper function on
     *
     * @var Post
     */
    protected $post;

    /**
     * Set up the test
     */
    public function setUp()
    {
        parent::setUp();

        $this->post = factory(Post::class)->make();
    }

    /** @test */
    public function it_returns_current_user_identifier_correctly1()
    {
        $this->assertNull(blameable_user($this->post));
    }

    /** @test */
    public function it_returns_current_user_identifier_correctly2()
    {
        $this->impersonateUser();

        $this->assertEquals($this->user->getKey(), blameable_user($this->post));
    }

    /** @test */
    public function it_returns_current_user_identifier_correctly3()
    {
        $this->impersonateOtherUser();

        $this->assertEquals($this->otherUser->getKey(), blameable_user($this->post));
    }
}
