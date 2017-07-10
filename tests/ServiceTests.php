<?php

namespace RichanFongdasen\EloquentBlameableTest;

use RichanFongdasen\EloquentBlameableTest\Models\Comment;
use RichanFongdasen\EloquentBlameableTest\Models\Post;
use RichanFongdasen\EloquentBlameableTest\Models\PostOverrideAttributes;
use RichanFongdasen\EloquentBlameableTest\Models\PostWithoutAttributes;
use RichanFongdasen\EloquentBlameableTest\Models\User;
use RichanFongdasen\EloquentBlameable\BlameableService;

class ServiceTests extends TestCase
{
    protected $service;

    public function setUp()
    {
        parent::setUp();

        $this->service = new BlameableService();
    }

    /** @test */
    public function it_returns_configurations_correctly1()
    {
        $expected = [
            'user' => User::class,
            'createdBy' => 'created_by',
            'updatedBy' => 'updated_by'
        ];

        $result = $this->invokeMethod($this->service, 'getConfigurations', [new Post()]);

        $this->assertEquals($expected, $result);
    }

    /** @test */
    public function it_returns_configurations_correctly2()
    {
        $expected = [
            'user' => User::class,
            'createdBy' => 'creator_id',
            'updatedBy' => 'updater_id'
        ];

        $result = $this->invokeMethod($this->service, 'getConfigurations', [new PostOverrideAttributes()]);

        $this->assertEquals($expected, $result);
    }

    /** @test */
    public function it_returns_configurations_correctly3()
    {
        $expected = [
            'user' => \App\User::class,
            'createdBy' => null,
            'updatedBy' => null
        ];

        $result = $this->invokeMethod($this->service, 'getConfigurations', [new PostWithoutAttributes()]);

        $this->assertEquals($expected, $result);
    }

    /** @test */
    public function it_returns_configuration_value_correctly1()
    {
        $model = new Comment();
        $result = $this->invokeMethod($this->service, 'getConfiguration', [$model, 'user']);

        $this->assertEquals(User::class, $result);
    }

    /** @test */
    public function it_returns_configuration_value_correctly2()
    {
        $model = new Comment();
        $result = $this->invokeMethod($this->service, 'getConfiguration', [$model, 'createdBy']);

        $this->assertEquals('user_id', $result);
    }

    /** @test */
    public function it_returns_configuration_value_correctly3()
    {
        $model = new Comment();
        $result = $this->invokeMethod($this->service, 'getConfiguration', [$model, 'updatedBy']);

        $this->assertEquals('updater_id', $result);
    }

    /** @test */
    public function it_would_set_attribute_value_correctly1()
    {
        $this->impersonateUser();
        $model = new Post();
        $result = $this->invokeMethod($this->service, 'setAttribute', [$model, 'createdBy']);

        $this->assertEquals($this->user->getKey(), $model->getAttribute('created_by'));
        $this->assertEquals('created_by', $result);
    }

    /** @test */
    public function it_would_set_attribute_value_correctly2()
    {
        $this->impersonateUser();
        $model = new Post();
        $result = $this->invokeMethod($this->service, 'setAttribute', [$model, 'createdBy', false]);

        $this->assertEquals(null, $model->getAttribute('created_by'));
        $this->assertEquals(null, $result);
    }

    /** @test */
    public function it_would_set_attribute_value_correctly3()
    {
        $this->impersonateUser();
        $model = new PostOverrideAttributes();
        $result = $this->invokeMethod($this->service, 'setAttribute', [$model, 'createdBy']);

        $this->assertEquals($this->user->getKey(), $model->getAttribute('creator_id'));
        $this->assertEquals('creator_id', $result);
    }

    /** @test */
    public function it_would_set_attribute_value_correctly4()
    {
        $this->impersonateUser();
        $model = new PostWithoutAttributes();
        $result = $this->invokeMethod($this->service, 'setAttribute', [$model, 'createdBy']);

        $this->assertEquals(null, $model->getAttribute('created_by'));
        $this->assertEquals(null, $model->getAttribute('creator_id'));
        $this->assertEquals(null, $result);
    }

    /** @test */
    public function it_updates_the_blameable_model_attributes_correctly1()
    {
        $this->impersonateUser();
        $model = new Post();
        $result = $this->service->updateAttributes($model);

        $this->assertEquals($this->user->getKey(), $model->getAttribute('created_by'));
        $this->assertEquals($this->user->getKey(), $model->getAttribute('updated_by'));
        $this->assertEquals(true, $result);
    }

    /** @test */
    public function it_updates_the_blameable_model_attributes_correctly2()
    {
        $this->impersonateUser();
        $model = new PostOverrideAttributes();
        $result = $this->service->updateAttributes($model);

        $this->assertEquals($this->user->getKey(), $model->getAttribute('creator_id'));
        $this->assertEquals($this->user->getKey(), $model->getAttribute('updater_id'));
        $this->assertEquals(true, $result);
    }

    /** @test */
    public function it_updates_the_blameable_model_attributes_correctly3()
    {
        $this->impersonateUser();
        $model = new PostWithoutAttributes();
        $result = $this->service->updateAttributes($model);

        $this->assertEquals(null, $model->getAttribute('created_by'));
        $this->assertEquals(null, $model->getAttribute('creator_id'));
        $this->assertEquals(null, $model->getAttribute('updated_by'));
        $this->assertEquals(null, $model->getAttribute('updater_id'));
        $this->assertEquals(false, $result);
    }
}
