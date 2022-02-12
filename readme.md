
![CI](https://github.com/richan-fongdasen/eloquent-blameable/workflows/CI/badge.svg?branch=master) 
[![codecov](https://codecov.io/gh/richan-fongdasen/eloquent-blameable/branch/master/graph/badge.svg)](https://codecov.io/gh/richan-fongdasen/eloquent-blameable)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/richan-fongdasen/eloquent-blameable/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/richan-fongdasen/eloquent-blameable/?branch=master)
[![Total Downloads](https://poser.pugx.org/richan-fongdasen/eloquent-blameable/d/total.svg)](https://packagist.org/packages/richan-fongdasen/eloquent-blameable)
[![Latest Stable Version](https://poser.pugx.org/richan-fongdasen/eloquent-blameable/v/stable.svg)](https://packagist.org/packages/richan-fongdasen/eloquent-blameable)
[![License: MIT](https://poser.pugx.org/laravel/framework/license.svg)](https://opensource.org/licenses/MIT)

# Eloquent Blameable

> Blameable behavior implementation for your Eloquent Model in Laravel

## Synopsis

This package would help you to track the creator and updater of each database record. It would be done by filling the specified attributes with the current user ID automatically. By default, those attributes would be filled when you are saving the Eloquent Model object.

## Table of contents

- [Setup](#setup)
- [Configuration](#configuration)
- [Usage](#usage)
- [License](#license)

## Setup

Install the package via Composer :

```sh
$ composer require richan-fongdasen/eloquent-blameable
```

### Laravel version compatibility

| Laravel version | Blameable version |
|:----------------|:------------------|
| 5.1.x           | 1.0.x             |
| 5.2.x - 5.4.x   | 1.1.x - 1.2.x     |
| 5.5.x - 5.8.x   | 1.3.x             |
| 6.x             | 1.4.x             |
| 7.x             | 1.5.x             |
| 8.x             | 1.6.x             |
| 9.x             | 1.8.x             |

> If you are using Laravel version 5.5+ then you can skip registering the service provider in your Laravel application.

### Service Provider

Add the package service provider in your `config/app.php`

```php
'providers' => [
    // ...
    RichanFongdasen\EloquentBlameable\ServiceProvider::class,
];
```

## Configuration

Publish configuration file using `php artisan` command

```sh
$ php artisan vendor:publish --provider="RichanFongdasen\EloquentBlameable\ServiceProvider"
```

The command above would copy a new configuration file to `/config/blameable.php`

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Guard
    |--------------------------------------------------------------------------
    |
    | Please specify your default authentication guard to be used by blameable
    | service. You can leave this to null if you're using the default Laravel
    | authentication guard.
    |
    | You can also override this value in model classes to use a different
    | authentication guard for your specific models.
    | IE: Some of your models can only be created / updated by specific users
    | who logged in from a specific authentication guard.
    |
    */

    'guard' => null,

    /*
    |--------------------------------------------------------------------------
    | User Model Definition
    |--------------------------------------------------------------------------
    |
    | Please specify a user model that should be used to setup `creator`
    | and `updater` relationship.
    |
    */

    'user' => \App\User::class,

    /*
    |--------------------------------------------------------------------------
    | The `createdBy` attribute
    |--------------------------------------------------------------------------
    |
    | Please define an attribute to use when recording the creator
    | identifier.
    |
    */

    'createdBy' => 'created_by',

    /*
    |--------------------------------------------------------------------------
    | The `updatedBy` attribute
    |--------------------------------------------------------------------------
    |
    | Please define an attribute to use when recording the updater
    | identifier.
    |
    */

    'updatedBy' => 'updated_by',

    /*
    |--------------------------------------------------------------------------
    | The `deletedBy` attribute
    |--------------------------------------------------------------------------
    |
    | Please define an attribute to use when recording the user
    | identifier who deleted the record. This feature would only work
    | if you are using SoftDeletes in your model.
    |
    */

    'deletedBy' => 'deleted_by',
];
```

## Usage

### Add some blameable attributes to your migrations

```php
Schema::create('some_tables', function (Blueprint $table) {
    // ...

    $table->integer('created_by')->nullable();
    $table->integer('updated_by')->nullable();
    $table->integer('deleted_by')->nullable();

    // ...



    /**
     * You can also create foreign key constrains
     * for the blameable attributes.
     */
    $table->foreign('created_by')
        ->references('id')->on('users')
        ->onDelete('cascade');

    $table->foreign('updated_by')
        ->references('id')->on('users')
        ->onDelete('cascade');
});
```

### Attach Blameable behavior into your Model

```php
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Post extends Model
{
    use BlameableTrait;

    // ...
}
```

### Override default configuration using static property

```php
    /**
     * You can override the default configuration
     * by defining this static property in your Model
     */
    protected static $blameable = [
        'guard' => 'customGuard',
        'user' => \App\User::class,
        'createdBy' => 'user_id',
        'updatedBy' => null
    ];
```

### Override default configuration using public method

```php
    /**
     * You can override the default configuration
     * by defining this method in your Model
     */
    public function blameable()
    {
        return [
            'guard' => 'customGuard',
            'user' => \App\User::class,
            'createdBy' => 'user_id',
            'updatedBy' => null
        ];
    }
```

### Using Blameable Query Scopes

```php
// Get all posts which have created by the given user id
Post::createdBy($userId)->get();

// Get all posts which have updated by the given user object
$user = User::findOrFail(1);
Post::updatedBy($user)->get();
```

### Accessing Creator / Updater Object

```php
// Get the creator user object
Post::findOrFail($postId)->creator;

// Get the updater user object
Post::findOrFail($postId)->updater;
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
