<?php

namespace RichanFongdasen\EloquentBlameableTest\Models;

use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class PostWithoutAttributes extends Post
{
    public function blameable()
    {
        return [
            'createdBy' => null,
            'updatedBy' => null,
        ];
    }
}
