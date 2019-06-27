<?php

namespace RichanFongdasen\EloquentBlameableTest\Supports\Models;

use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class PostWithoutAttributes extends Post
{
    protected static $blameable = [
        'user' => 'App\User',
        'createdBy' => null,
        'updatedBy' => null,
        'deletedBy' => null,
    ];
}
