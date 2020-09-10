<?php

namespace RichanFongdasen\EloquentBlameableTest\Supports\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Post extends Model
{
    use BlameableTrait;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content'
    ];

    protected $table = 'posts';
}
