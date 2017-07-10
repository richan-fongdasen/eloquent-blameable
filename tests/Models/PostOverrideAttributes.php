<?php

namespace RichanFongdasen\EloquentBlameableTest\Models;

use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class PostOverrideAttributes extends Post
{
    public function blameable()
    {
        return [
            'user' => User::class,
            'createdBy' => 'creator_id',
            'updatedBy' => 'updater_id',
        ];
    }
}
