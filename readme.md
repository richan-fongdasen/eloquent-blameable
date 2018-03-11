[![Build Status](https://travis-ci.org/richan-fongdasen/eloquent-blameable.svg?branch=master)](https://travis-ci.org/richan-fongdasen/eloquent-blameable) 
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

* [Setup](#setup)
* [Configuration](#configuration)
* [Usage](#usage)
* [License](#license)

## Setup

Install the package via Composer :
```sh
$ composer require richan-fongdasen/eloquent-blameable
```

### Laravel version compatibility

This package supports laravel versions from ``5.1.35`` to ``5.5.*``

> If you are using Laravel version 5.5+ then you can skip registering the service provider in your Laravel application.

### Service Provider

Add the package service provider in your ``config/app.php``

```php
'providers' => [
    // ...
    RichanFongdasen\EloquentBlameable\ServiceProvider::class,
];
```

## Configuration

Publish configuration file using ``php artisan`` command

```sh
$ php artisan vendor:publish --provider="RichanFongdasen\EloquentBlameable\ServiceProvider"
```

The command above would copy a new configuration file to ``/config/blameable.php``

```php
return [

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

### Override default configuration

```php
    /**
     * You can override the default configuration
     * by defining this method in your Model
     */
    public function blameable()
    {
        return [
            'user' => \App\User::class,
            'createdBy' => 'user_id',
            'updatedBy' => null
        ];
    }
```

## Usage

### Attach Blameable behavior into your Model

```php
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Post extends Model
{
    use BlameableTrait;

    // ...
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
