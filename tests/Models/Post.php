<?php

namespace RichanFongdasen\EloquentBlameableTest\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Post extends Model
{
    use BlameableTrait;
    use SoftDeletes;
    
    protected $fillable = [
        'title',
        'content'
    ];

    protected $table = 'posts';

    public function blameable()
    {
        return [
            'user' => User::class
        ];
    }
}
