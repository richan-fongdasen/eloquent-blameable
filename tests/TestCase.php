<?php

namespace RichanFongdasen\EloquentBlameableTest;

use Illuminate\Database\Eloquent\Factory as ModelFactory;
use Orchestra\Testbench\TestCase as BaseTest;
use RichanFongdasen\EloquentBlameableTest\Supports\Models\Admin;
use RichanFongdasen\EloquentBlameableTest\Supports\Models\User;
use RichanFongdasen\EloquentBlameable\BlameableService;

abstract class TestCase extends BaseTest
{
    /**
     * Base admin user
     *
     * @var \RichanFongdasen\EloquentBlameableTest\Supports\Models\Admin
     */
    protected $admin;

    /**
     * Base user
     *
     * @var \RichanFongdasen\EloquentBlameableTest\Supports\Models\User
     */
    protected $user;

    /**
     * Another user
     *
     * @var \RichanFongdasen\EloquentBlameableTest\Supports\Models\User
     */
    protected $otherUser;

    /**
     * Define environment setup
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('auth.guards', [
            'user' => [
                'driver' => 'session',
                'provider' => 'user',
            ],
            'admin' => [
                'driver' => 'session',
                'provider' => 'admin',
            ]
        ]);
        $app['config']->set('auth.providers', [
            'user' => [
                'driver' => 'eloquent',
                'model' => User::class,
            ],
            'admin' => [
                'driver' => 'eloquent',
                'model' => Admin::class,
            ]
        ]);
        $app['config']->set('auth.defaults.guard', 'user');
        $app['config']->set('blameable.user', User::class);

        app(BlameableService::class)->loadConfig();
    }

    /**
     * Define package service provider
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \RichanFongdasen\EloquentBlameable\ServiceProvider::class,
            \Orchestra\Database\ConsoleServiceProvider::class
        ];
    }

    /**
     * Impersonate an admin before updating a blameable model
     *
     * @return void
     */
    protected function impersonateAdmin() :void
    {
        $this->admin = factory(Admin::class)->create([
            'id' => rand(300, 900)
        ]);
        $this->actingAs($this->admin, 'admin');
    }

    /**
     * Impersonate another user before updating a blameable model
     *
     * @return void
     * @throws \Exception
     */
    protected function impersonateOtherUser() :void
    {
        $this->otherUser = factory(User::class)->create([
            'id' => random_int(1000, 2000)
        ]);
        $this->actingAs($this->otherUser);
    }

    /**
     * Impersonate a user before updating a blameable model
     *
     * @return void
     * @throws \Exception
     */
    protected function impersonateUser() :void
    {
        $this->user = factory(User::class)->create([
            'id' => random_int(200, 900)
        ]);
        $this->actingAs($this->user);
    }

    /**
     * Invoke protected / private method of the given object
     *
     * @param  Object $object
     * @param  String $methodName
     * @param  array  $parameters
     * @return mixed
     * @throws \Exception
     */
    protected function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Prepare database requirements
     * to perform any tests.
     *
     * @param  string $migrationPath
     * @param  string $factoryPath
     * @return void
     */
    protected function prepareDatabase($migrationPath, $factoryPath = null) :void
    {
        $this->loadMigrationsFrom($migrationPath);

        if (!$factoryPath) {
            return;
        }

        if (method_exists($this, 'withFactories')) {
            $this->withFactories($factoryPath);
        } else {
            $this->app->make(ModelFactory::class)->load($factoryPath);
        }
    }

    /**
     * Prepare to get an exception in a test
     *
     * @param  mixed $exception
     * @return void
     */
    protected function prepareException($exception) :void
    {
        if (method_exists($this, 'expectException')) {
            $this->expectException($exception);
        } elseif (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException($exception);
        }
    }

    /**
     * Setup the test environment
     *
     * @return void
     */
    public function setUp() :void
    {
        parent::setUp();

        $this->prepareDatabase(
            realpath(__DIR__.'/Supports/database/migrations'),
            realpath(__DIR__.'/Supports/database/factories')
        );
    }
}
