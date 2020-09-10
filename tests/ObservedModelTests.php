<?php

namespace RichanFongdasen\EloquentBlameableTest;

use RichanFongdasen\EloquentBlameableTest\Supports\Models\Comment;
use RichanFongdasen\EloquentBlameableTest\Supports\Models\News;
use RichanFongdasen\EloquentBlameableTest\Supports\Models\Post;
use RichanFongdasen\EloquentBlameableTest\Supports\Models\User;

class ObservedModelTests extends TestCase
{
    /** @test */
    public function it_works_perfectly_on_creating_a_new_post()
    {
        $this->impersonateUser();
        $post = Post::factory()->create();

        $this->assertFalse($post->isDirty('created_by'));
        $this->assertFalse($post->isDirty('updated_by'));
        $this->assertEquals($this->user->getKey(), $post->getAttribute('created_by'));
        $this->assertEquals($this->user->getKey(), $post->getAttribute('updated_by'));
    }

    /** @test */
    public function it_works_perfectly_on_updating_existing_post()
    {
        $this->impersonateUser();
        $post = Post::factory()->create();

        $this->impersonateOtherUser();
        $post->setAttribute('title', 'Another Title');
        $post->save();

        $this->assertFalse($post->isDirty('created_by'));
        $this->assertFalse($post->isDirty('updated_by'));
        $this->assertEquals($this->user->getKey(), $post->getAttribute('created_by'));
        $this->assertEquals($this->otherUser->getKey(), $post->getAttribute('updated_by'));
    }

    /** @test */
    public function it_works_perfectly_on_deleting_post1()
    {
        $this->impersonateUser();
        $post = Post::factory()->create();

        $post->delete();
        $deletedPost = Post::onlyTrashed()->where('id', $post->getKey())->first();

        $this->assertEquals($this->user->getKey(), $deletedPost->getAttribute('deleted_by'));
    }

    /** @test */
    public function it_works_perfectly_on_deleting_post2()
    {
        $this->impersonateUser();
        $post = Post::factory()->create();

        $this->impersonateOtherUser();
        $post->delete();
        $deletedPost = Post::onlyTrashed()->where('id', $post->getKey())->first();

        $this->assertEquals($this->user->getKey(), $deletedPost->getAttribute('updated_by'));
        $this->assertEquals($this->otherUser->getKey(), $deletedPost->getAttribute('deleted_by'));
    }

    /** @test */
    public function it_works_perfectly_on_restoring_deleted_post()
    {
        $this->impersonateUser();
        $post = Post::factory()->create();

        $this->impersonateOtherUser();
        $post->delete();
        Post::onlyTrashed()->where('id', $post->getKey())->first()->restore();
        $restoredPost = Post::where('id', $post->getKey())->first();

        $this->assertNull($restoredPost->getAttribute('deleted_by'));
    }

    /** @test */
    public function it_works_perfectly_on_creating_a_new_comment()
    {
        $this->impersonateUser();
        $comment = Comment::factory()->create([
            'post_id' => 100
        ]);

        $this->assertFalse($comment->isDirty('created_by'));
        $this->assertFalse($comment->isDirty('updated_by'));
        $this->assertEquals($this->user->getKey(), $comment->getAttribute('user_id'));
        $this->assertEquals($this->user->getKey(), $comment->getAttribute('updater_id'));
    }

    /** @test */
    public function it_works_perfectly_on_updating_existing_comment()
    {
        $this->impersonateUser();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->getKey()
        ]);

        $this->impersonateOtherUser();
        $comment->setAttribute('content', 'Another Content');
        $comment->save();
        $updatedPost = Post::where('id', $post->getKey())->first();

        $this->assertFalse($comment->isDirty('created_by'));
        $this->assertFalse($comment->isDirty('updated_by'));
        $this->assertEquals($this->user->getKey(), $comment->getAttribute('user_id'));
        $this->assertEquals($this->otherUser->getKey(), $comment->getAttribute('updater_id'));

        // blameable attributes should not be updated on any touched models
        $this->assertEquals($this->user->getKey(), $updatedPost->getAttribute('created_by'));
        $this->assertEquals($this->user->getKey(), $updatedPost->getAttribute('updated_by'));
    }

    /** @test */
    public function it_works_perfectly_on_deleting_comment1()
    {
        $this->impersonateUser();
        $comment = Comment::factory()->create([
            'post_id' => 100
        ]);

        $comment->delete();
        $deletedComment = Comment::onlyTrashed()->where('id', $comment->getKey())->first();

        $this->assertEquals($this->user->getKey(), $deletedComment->getAttribute('eraser_id'));
    }

    /** @test */
    public function it_works_perfectly_on_deleting_comment2()
    {
        $this->impersonateUser();
        $comment = Comment::factory()->create([
            'post_id' => 100
        ]);

        $this->impersonateOtherUser();
        $comment->delete();
        $deletedComment = Comment::onlyTrashed()->where('id', $comment->getKey())->first();

        $this->assertEquals($this->user->getKey(), $deletedComment->getAttribute('updater_id'));
        $this->assertEquals($this->otherUser->getKey(), $deletedComment->getAttribute('eraser_id'));
    }

    /** @test */
    public function it_works_perfectly_on_restoring_deleted_comment()
    {
        $this->impersonateUser();
        $comment = Comment::factory()->create([
            'post_id' => 100
        ]);

        $this->impersonateOtherUser();
        $comment->delete();
        Comment::onlyTrashed()->where('id', $comment->getKey())->first()->restore();
        $restoredComment = Comment::where('id', $comment->getKey())->first();

        $this->assertNull($restoredComment->getAttribute('deleted_by'));
    }

    /** @test */
    public function it_works_perfectly_on_creating_a_new_user1()
    {
        $this->impersonateUser();

        $this->assertFalse($this->user->isDirty('created_by'));
        $this->assertFalse($this->user->isDirty('updated_by'));
        $this->assertNull($this->user->getAttribute('created_by'));
        $this->assertNull($this->user->getAttribute('updated_by'));
    }

    /** @test */
    public function it_works_perfectly_on_creating_a_new_user2()
    {
        $this->impersonateUser();
        $this->impersonateOtherUser();

        $this->assertFalse($this->otherUser->isDirty('created_by'));
        $this->assertFalse($this->otherUser->isDirty('updated_by'));
        $this->assertEquals($this->user->getKey(), $this->otherUser->getAttribute('created_by'));
        $this->assertEquals($this->user->getKey(), $this->otherUser->getAttribute('updated_by'));
    }

    /** @test */
    public function it_works_perfectly_on_updating_existing_user()
    {
        $this->impersonateUser();
        $user = User::factory()->create();

        $this->impersonateOtherUser();
        $user->setAttribute('email', 'another@email.com');
        $user->save();

        $this->assertFalse($user->isDirty('created_by'));
        $this->assertFalse($user->isDirty('updated_by'));
        $this->assertEquals($this->user->getKey(), $user->getAttribute('created_by'));
        $this->assertEquals($this->otherUser->getKey(), $user->getAttribute('updated_by'));
    }

    /** @test */
    public function it_works_perfectly_on_deleting_user1()
    {
        $this->impersonateUser();
        $user = User::factory()->create();

        $user->delete();
        $deletedUser = User::onlyTrashed()->where('id', $user->getKey())->first();

        $this->assertEquals($this->user->getKey(), $deletedUser->getAttribute('deleted_by'));
    }

    /** @test */
    public function it_works_perfectly_on_deleting_user2()
    {
        $this->impersonateUser();
        $user = User::factory()->create();

        $this->impersonateOtherUser();
        $user->delete();
        $deletedUser = User::onlyTrashed()->where('id', $user->getKey())->first();

        $this->assertEquals($this->user->getKey(), $deletedUser->getAttribute('updated_by'));
        $this->assertEquals($this->otherUser->getKey(), $deletedUser->getAttribute('deleted_by'));
    }

    /** @test */
    public function it_works_perfectly_on_restoring_deleted_user()
    {
        $this->impersonateUser();
        $user = User::factory()->create();

        $this->impersonateOtherUser();
        $user->delete();
        User::onlyTrashed()->where('id', $user->getKey())->first()->restore();
        $restoredUser = User::where('id', $user->getKey())->first();

        $this->assertNull($restoredUser->getAttribute('deleted_by'));
    }

    /** @test */
    public function it_will_set_null_creator_and_null_updater_on_unauthenticated_user()
    {
        $post = Post::factory()->create();

        $this->assertNull($post->getAttribute('created_by'));
        $this->assertNull($post->getAttribute('updated_by'));
    }

    /** @test */
    public function it_wont_cause_any_error_when_deleting_model_without_soft_deletes()
    {
        $this->impersonateAdmin();

        $news = News::factory()->create();
        $news->delete();
        $news->fresh();

        $this->assertEquals($this->admin->getKey(), $news->getAttribute('created_by'));
        $this->assertEquals($this->admin->getKey(), $news->getAttribute('updated_by'));
        $this->assertFalse($news->exists);
        $this->assertCount(0, $news->getDirty());
    }
}
