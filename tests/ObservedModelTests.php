<?php

namespace RichanFongdasen\EloquentBlameableTest;

use RichanFongdasen\EloquentBlameableTest\Models\Comment;
use RichanFongdasen\EloquentBlameableTest\Models\Post;
use RichanFongdasen\EloquentBlameableTest\Models\User;
use RichanFongdasen\EloquentBlameable\BlameableService;
use RichanFongdasen\EloquentBlameable\Exceptions\UndefinedUserModelException;

class ObservedModelTests extends TestCase
{
    /** @test */
    public function it_works_perfectly_on_creating_a_new_post()
    {
        $this->impersonateUser();
        $post = factory(Post::class)->create();

        $this->assertEquals($this->user->getKey(), $post->getAttribute('created_by'));
        $this->assertEquals($this->user->getKey(), $post->getAttribute('updated_by'));
    }

    /** @test */
    public function it_works_perfectly_on_updating_existing_post()
    {
        $this->impersonateUser();
        $post = factory(Post::class)->create();

        $this->impersonateOtherUser();
        $post->setAttribute('title', 'Another Title');
        $post->save();

        $this->assertEquals($this->user->getKey(), $post->getAttribute('created_by'));
        $this->assertEquals($this->otherUser->getKey(), $post->getAttribute('updated_by'));
    }

    /** @test */
    public function it_works_perfectly_on_creating_a_new_comment()
    {
        $this->impersonateUser();
        $comment = factory(Comment::class)->create([
            'post_id' => 100
        ]);

        $this->assertEquals($this->user->getKey(), $comment->getAttribute('user_id'));
        $this->assertEquals($this->user->getKey(), $comment->getAttribute('updater_id'));
    }

    /** @test */
    public function it_works_perfectly_on_updating_existing_comment()
    {
        $this->impersonateUser();
        $post = factory(Post::class)->create();
        $comment = factory(Comment::class)->create([
            'post_id' => $post->getKey()
        ]);

        $this->impersonateOtherUser();
        $comment->setAttribute('content', 'Another Content');
        $comment->save();

        $updatedPost = Post::where('id', $post->getKey())->first();

        $this->assertEquals($this->user->getKey(), $comment->getAttribute('user_id'));
        $this->assertEquals($this->otherUser->getKey(), $comment->getAttribute('updater_id'));

        // blameable attributes should not be updated on any touched models
        $this->assertEquals($this->user->getKey(), $updatedPost->getAttribute('created_by'));
        $this->assertEquals($this->user->getKey(), $updatedPost->getAttribute('updated_by'));
    }
}
