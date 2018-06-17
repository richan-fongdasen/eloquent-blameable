<?php

namespace RichanFongdasen\EloquentBlameableTest;

use RichanFongdasen\EloquentBlameableTest\Models\Admin;
use RichanFongdasen\EloquentBlameableTest\Models\Article;
use RichanFongdasen\EloquentBlameableTest\Models\Comment;
use RichanFongdasen\EloquentBlameableTest\Models\Post;
use RichanFongdasen\EloquentBlameableTest\Models\User;
use RichanFongdasen\EloquentBlameable\BlameableService;
use RichanFongdasen\EloquentBlameable\Exceptions\UndefinedUserModelException;

class TraitTests extends TestCase
{
    protected $articleA;
    protected $articleB;
    protected $articleC;

    protected $postA;
    protected $postB;
    protected $postC;
    protected $postD;
    protected $postE;

    public function setUp()
    {
        parent::setUp();

        $this->impersonateAdmin();
        $this->articleA = factory(Article::class)->create();
        $this->articleB = factory(Article::class)->create();
        $this->articleC = factory(Article::class)->create();

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
    public function post_model_would_accept_created_by_scope1()
    {
        $collections = Post::createdBy($this->user->getKey())->get();

        $this->assertTrue($collections->contains($this->postA));
        $this->assertTrue($collections->contains($this->postB));
        $this->assertTrue($collections->contains($this->postC));
    }

    /** @test */
    public function post_model_would_accept_created_by_scope2()
    {
        $collections = Post::createdBy($this->otherUser)->get();

        $this->assertTrue($collections->contains($this->postD));
        $this->assertTrue($collections->contains($this->postE));
    }

    /** @test */
    public function post_model_would_accept_updated_by_scope1()
    {
        $collections = Post::updatedBy($this->user->getKey())->get();

        $this->assertTrue($collections->contains($this->postA));
        $this->assertTrue($collections->contains($this->postC));
    }

    /** @test */
    public function post_model_would_accept_updated_by_scope2()
    {
        $collections = Post::updatedBy($this->otherUser)->get();

        $this->assertTrue($collections->contains($this->postB));
        $this->assertTrue($collections->contains($this->postD));
        $this->assertTrue($collections->contains($this->postE));
    }

    /** @test */
    public function it_returns_creator_user_object_correctly()
    {
        $this->assertInstanceOf(User::class, $this->postA->creator);
        $this->assertInstanceOf(User::class, $this->postB->creator);
        $this->assertInstanceOf(User::class, $this->postC->creator);
        $this->assertInstanceOf(User::class, $this->postD->creator);
        $this->assertInstanceOf(User::class, $this->postE->creator);

        $this->assertEquals($this->user->getKey(), $this->postA->creator->getKey());
        $this->assertEquals($this->user->getKey(), $this->postB->creator->getKey());
        $this->assertEquals($this->user->getKey(), $this->postC->creator->getKey());
        $this->assertEquals($this->otherUser->getKey(), $this->postD->creator->getKey());
        $this->assertEquals($this->otherUser->getKey(), $this->postE->creator->getKey());
    }

    /** @test */
    public function it_returns_updater_user_object_correctly()
    {
        $this->assertInstanceOf(User::class, $this->postA->updater);
        $this->assertInstanceOf(User::class, $this->postB->updater);
        $this->assertInstanceOf(User::class, $this->postC->updater);
        $this->assertInstanceOf(User::class, $this->postD->updater);
        $this->assertInstanceOf(User::class, $this->postE->updater);
        
        $this->assertEquals($this->user->getKey(), $this->postA->updater->getKey());
        $this->assertEquals($this->user->getKey(), $this->postC->updater->getKey());
        $this->assertEquals($this->otherUser->getKey(), $this->postB->updater->getKey());
        $this->assertEquals($this->otherUser->getKey(), $this->postD->updater->getKey());
        $this->assertEquals($this->otherUser->getKey(), $this->postE->updater->getKey());
    }

    /** @test */
    public function article_model_would_accept_created_by_scope()
    {
        $collections = Article::createdBy($this->admin)->get();

        $this->assertTrue($collections->contains($this->articleA));
        $this->assertTrue($collections->contains($this->articleB));
        $this->assertTrue($collections->contains($this->articleC));
    }

    /** @test */
    public function article_model_would_accept_updated_by_scope()
    {
        $collections = Article::updatedBy($this->admin)->get();

        $this->assertTrue($collections->contains($this->articleA));
        $this->assertTrue($collections->contains($this->articleB));
        $this->assertTrue($collections->contains($this->articleC));
    }

    /** @test */
    public function it_returns_creator_admin_object_correctly()
    {
        $this->assertInstanceOf(Admin::class, $this->articleA->creator);
        $this->assertInstanceOf(Admin::class, $this->articleB->creator);
        $this->assertInstanceOf(Admin::class, $this->articleC->creator);

        $this->assertEquals($this->admin->getKey(), $this->articleA->creator->getKey());
        $this->assertEquals($this->admin->getKey(), $this->articleB->creator->getKey());
        $this->assertEquals($this->admin->getKey(), $this->articleC->creator->getKey());
    }

    /** @test */
    public function it_returns_updater_admin_object_correctly()
    {
        $this->assertInstanceOf(Admin::class, $this->articleA->updater);
        $this->assertInstanceOf(Admin::class, $this->articleB->updater);
        $this->assertInstanceOf(Admin::class, $this->articleC->updater);

        $this->assertEquals($this->admin->getKey(), $this->articleA->updater->getKey());
        $this->assertEquals($this->admin->getKey(), $this->articleB->updater->getKey());
        $this->assertEquals($this->admin->getKey(), $this->articleC->updater->getKey());
    }
}
