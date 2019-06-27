<?php

namespace RichanFongdasen\EloquentBlameableTest\Supports\Models;

use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class PostOverrideAttributes extends Post
{
    protected static $blameable = [
        'createdBy' => 'creator_id',
        'updatedBy' => 'updater_id',
        'deletedBy' => 'eraser_id',
    ];
}
