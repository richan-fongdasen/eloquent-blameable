<?php

namespace RichanFongdasen\EloquentBlameableTest\Models;

use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Post extends Model
{
    use BlameableTrait;
    
    protected $fillable = [
        'title',
        'content'
    ];

    protected $table = 'posts';

    public function blameable()
    {
        return \RichanFongdasen\EloquentBlameableTest\Models\User::class;
    }
}
