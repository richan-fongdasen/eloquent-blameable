<?php

namespace RichanFongdasen\EloquentBlameableTest\Models;

use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameableTest\Models\Post;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Comment extends Model
{
    use BlameableTrait;
    
    protected $fillable = [
        'content'
    ];

    protected $touches = [
        'post'
    ];

    public function blameable()
    {
        return [
            'created_by' => 'user_id',
            'updated_by' => 'updater_id',
            'user' => \RichanFongdasen\EloquentBlameableTest\Models\User::class
        ];
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
