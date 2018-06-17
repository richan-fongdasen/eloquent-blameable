<?php

namespace RichanFongdasen\EloquentBlameableTest\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Article extends Model
{
    use BlameableTrait;
    use SoftDeletes;

    protected static $blameable = [
        'guard' => 'admin',
        'user' => Admin::class,
    ];
    
    protected $fillable = [
        'title',
        'content'
    ];

    protected $table = 'posts';
}
