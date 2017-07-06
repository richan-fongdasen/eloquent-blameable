<?php

namespace RichanFongdasen\EloquentBlameableTest;

use RichanFongdasen\EloquentBlameableTest\Models\Post;
use RichanFongdasen\EloquentBlameableTest\Models\PostExplicit;
use RichanFongdasen\EloquentBlameableTest\Models\PostExplicitWithoutAttributes;
use RichanFongdasen\EloquentBlameableTest\Models\PostExplicitWithoutCreatedBy;
use RichanFongdasen\EloquentBlameableTest\Models\PostExplicitWithoutUpdatedBy;
use RichanFongdasen\EloquentBlameableTest\Models\User;
use RichanFongdasen\EloquentBlameable\BlameableService;
use RichanFongdasen\EloquentBlameable\Exceptions\UndefinedUserModelException;

class ServiceTests extends TestCase
{
    protected $mockedModel;
    protected $service;

    public function setUp()
    {
        parent::setUp();

        $this->service = new BlameableService();
        $this->mockedModel = \Mockery::mock(Post::class);
    }

    protected function impersonateUser()
    {
        $this->user = factory(User::class)->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_returns_configuration_value_correctly1()
    {
        $config = User::class;
        $result = $this->invokeMethod($this->service, 'getConfiguration', [$config, 'one', 'one']);

        $this->assertEquals('one', $result);
    }

    /** @test */
    public function it_returns_configuration_value_correctly2()
    {
        $config = [
            'user' => User::class,
        ];
        $result = $this->invokeMethod($this->service, 'getConfiguration', [$config, 'one', 'one']);

        $this->assertEquals('one', $result);
    }

    /** @test */
    public function it_returns_configuration_value_correctly3()
    {
        $config = [
            'user' => User::class,
            'one' => 'two'
        ];
        $result = $this->invokeMethod($this->service, 'getConfiguration', [$config, 'one', 'one']);

        $this->assertEquals('two', $result);
    }

    /** @test */
    public function it_returns_configuration_value_correctly4()
    {
        $config = [
            'user' => User::class,
            'one' => null
        ];
        $result = $this->invokeMethod($this->service, 'getConfiguration', [$config, 'one', 'one']);

        $this->assertEquals(null, $result);
    }

    /** @test */
    public function it_returns_attribute_name_correctly1()
    {
        $this->mockedModel->shouldReceive('blameable')->andReturn(User::class);

        $this->assertEquals('one', $this->service->getAttributeName($this->mockedModel, 'one'));
    }

    /** @test */
    public function it_returns_attribute_name_correctly2()
    {
        $this->mockedModel->shouldReceive('blameable')->andReturn([
            'user' => User::class,
        ]);

        $this->assertEquals('one', $this->service->getAttributeName($this->mockedModel, 'one'));
    }

    /** @test */
    public function it_returns_attribute_name_correctly3()
    {
        $this->mockedModel->shouldReceive('blameable')->andReturn([
            'user' => User::class,
            'one' => 'two'
        ]);

        $this->assertEquals('two', $this->service->getAttributeName($this->mockedModel, 'one'));
    }

    /** @test */
    public function it_returns_attribute_name_correctly4()
    {
        $this->mockedModel->shouldReceive('blameable')->andReturn([
            'user' => User::class,
            'one' => null
        ]);

        $this->assertEquals(null, $this->service->getAttributeName($this->mockedModel, 'one'));
    }

    /** @test */
    public function it_returns_user_model_classname_correctly1()
    {
        $this->mockedModel->shouldReceive('blameable')->andReturn(User::class);

        $this->assertEquals(User::class, $this->service->getUserModel($this->mockedModel));
    }

    /** @test */
    public function it_returns_user_model_classname_correctly2()
    {
        $this->mockedModel->shouldReceive('blameable')->andReturn([
            'user' => User::class
        ]);

        $this->assertEquals(User::class, $this->service->getUserModel($this->mockedModel));
    }

    /** @test */
    public function it_throws_exception_if_the_user_model_was_undefined1()
    {
        $this->mockedModel->shouldReceive('blameable')->andReturn([
            'user' => null
        ]);

        $this->prepareException(UndefinedUserModelException::class);

        $this->service->getUserModel($this->mockedModel);
    }

    /** @test */
    public function it_throws_exception_if_the_user_model_was_undefined2()
    {
        $this->mockedModel->shouldReceive('blameable')->andReturn(null);

        $this->prepareException(UndefinedUserModelException::class);

        $this->service->getUserModel($this->mockedModel);
    }

    /** @test */
    public function it_would_set_attribute_value_correctly1()
    {
        $this->impersonateUser();
        $model = new Post();
        $result = $this->invokeMethod($this->service, 'setAttribute', [$model, 'created_by']);

        $this->assertEquals($this->user->getKey(), $model->getAttribute('created_by'));
        $this->assertEquals('created_by', $result);
    }

    /** @test */
    public function it_would_set_attribute_value_correctly2()
    {
        $this->impersonateUser();
        $model = new Post();
        $result = $this->invokeMethod($this->service, 'setAttribute', [$model, 'created_by', false]);

        $this->assertEquals(null, $model->getAttribute('created_by'));
        $this->assertEquals(null, $result);
    }

    /** @test */
    public function it_would_set_attribute_value_correctly3()
    {
        $this->impersonateUser();
        $model = new PostExplicit();
        $result = $this->invokeMethod($this->service, 'setAttribute', [$model, 'created_by']);

        $this->assertEquals($this->user->getKey(), $model->getAttribute('creator_id'));
        $this->assertEquals('creator_id', $result);
    }

    /** @test */
    public function it_would_set_attribute_value_correctly4()
    {
        $this->impersonateUser();
        $model = new PostExplicit();
        $result = $this->invokeMethod($this->service, 'setAttribute', [$model, 'created_by', false]);

        $this->assertEquals(null, $model->getAttribute('creator_id'));
        $this->assertEquals(null, $result);
    }

    /** @test */
    public function it_would_set_attribute_value_correctly5()
    {
        $this->impersonateUser();
        $model = new PostExplicitWithoutAttributes();
        $result = $this->invokeMethod($this->service, 'setAttribute', [$model, 'created_by']);

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
        $model = new PostExplicit();
        $result = $this->service->updateAttributes($model);

        $this->assertEquals($this->user->getKey(), $model->getAttribute('creator_id'));
        $this->assertEquals($this->user->getKey(), $model->getAttribute('updater_id'));
        $this->assertEquals(true, $result);
    }

    /** @test */
    public function it_updates_the_blameable_model_attributes_correctly3()
    {
        $this->impersonateUser();
        $model = new PostExplicitWithoutUpdatedBy();
        $result = $this->service->updateAttributes($model);

        $this->assertEquals($this->user->getKey(), $model->getAttribute('creator_id'));
        $this->assertEquals(null, $model->getAttribute('updated_by'));
        $this->assertEquals(null, $model->getAttribute('updater_id'));
        $this->assertEquals(true, $result);
    }

    /** @test */
    public function it_updates_the_blameable_model_attributes_correctly4()
    {
        $this->impersonateUser();
        $model = new PostExplicitWithoutCreatedBy();
        $result = $this->service->updateAttributes($model);

        $this->assertEquals($this->user->getKey(), $model->getAttribute('updater_id'));
        $this->assertEquals(null, $model->getAttribute('created_by'));
        $this->assertEquals(null, $model->getAttribute('creator_id'));
        $this->assertEquals(true, $result);
    }

    /** @test */
    public function it_updates_the_blameable_model_attributes_correctly5()
    {
        $this->impersonateUser();
        $model = new PostExplicitWithoutAttributes();
        $result = $this->service->updateAttributes($model);

        $this->assertEquals(null, $model->getAttribute('created_by'));
        $this->assertEquals(null, $model->getAttribute('updated_by'));
        $this->assertEquals(null, $model->getAttribute('creator_id'));
        $this->assertEquals(null, $model->getAttribute('updater_id'));
        $this->assertEquals(false, $result);
    }
}
