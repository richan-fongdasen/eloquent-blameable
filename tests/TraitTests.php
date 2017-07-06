<?php

namespace RichanFongdasen\EloquentBlameableTest;

use RichanFongdasen\EloquentBlameableTest\Models\Comment;
use RichanFongdasen\EloquentBlameableTest\Models\Post;
use RichanFongdasen\EloquentBlameableTest\Models\User;
use RichanFongdasen\EloquentBlameable\BlameableService;
use RichanFongdasen\EloquentBlameable\Exceptions\UndefinedUserModelException;

class TraitTests extends TestCase
{
    protected $postA;
    protected $postB;
    protected $postC;
    protected $postD;
    protected $postE;

    public function setUp()
    {
        parent::setUp();

        $this->impersonateUser();
        $this->postA = factory(Post::class)->create();
        $this->postB = factory(Post::class)->create();
        $this->postC = factory(Post::class)->create();

        $this->impersonateOtherUser();
        $this->postD = factory(Post::class)->create();
        $this->postE = factory(Post::class)->create();
        $this->postB->save();
    }

    /** @test */
    public function model_would_accept_created_by_scope1()
    {
        $collections = Post::createdBy($this->user->getKey())->get();

        $this->assertTrue($collections->contains($this->postA));
        $this->assertTrue($collections->contains($this->postB));
        $this->assertTrue($collections->contains($this->postC));
    }

    /** @test */
    public function model_would_accept_created_by_scope2()
    {
        $collections = Post::createdBy($this->otherUser->getKey())->get();

        $this->assertTrue($collections->contains($this->postD));
        $this->assertTrue($collections->contains($this->postE));
    }

    /** @test */
    public function model_would_accept_updated_by_scope1()
    {
        $collections = Post::updatedBy($this->user->getKey())->get();

        $this->assertTrue($collections->contains($this->postA));
        $this->assertTrue($collections->contains($this->postC));
    }

    /** @test */
    public function model_would_accept_updated_by_scope2()
    {
        $collections = Post::updatedBy($this->otherUser->getKey())->get();

        $this->assertTrue($collections->contains($this->postB));
        $this->assertTrue($collections->contains($this->postD));
        $this->assertTrue($collections->contains($this->postE));
    }

    /** @test */
    public function it_returns_creator_user_object_correctly()
    {
        $this->assertEquals($this->user->getKey(), $this->postA->creator->getKey());
        $this->assertEquals($this->user->getKey(), $this->postB->creator->getKey());
        $this->assertEquals($this->user->getKey(), $this->postC->creator->getKey());
        $this->assertEquals($this->otherUser->getKey(), $this->postD->creator->getKey());
        $this->assertEquals($this->otherUser->getKey(), $this->postE->creator->getKey());
    }

    /** @test */
    public function it_returns_updater_user_object_correctly()
    {
        $this->assertEquals($this->user->getKey(), $this->postA->updater->getKey());
        $this->assertEquals($this->user->getKey(), $this->postC->updater->getKey());
        $this->assertEquals($this->otherUser->getKey(), $this->postB->updater->getKey());
        $this->assertEquals($this->otherUser->getKey(), $this->postD->updater->getKey());
        $this->assertEquals($this->otherUser->getKey(), $this->postE->updater->getKey());
    }
}
