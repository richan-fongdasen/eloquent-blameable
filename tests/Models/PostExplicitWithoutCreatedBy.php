<?php

namespace RichanFongdasen\EloquentBlameableTest\Models;

use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameableTest\Models\Post;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class PostExplicitWithoutCreatedBy extends Post
{
    public function blameable()
    {
        return [
            'user' => \RichanFongdasen\EloquentBlameableTest\Models\User::class,
            'created_by' => null,
            'updated_by' => 'updater_id',
        ];
    }
}
