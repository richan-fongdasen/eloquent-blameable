<?php

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
