<?php

namespace RichanFongdasen\EloquentBlameableTest\Supports\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class News extends Model
{
    use BlameableTrait;
    use HasFactory;

    protected static $blameable = [
        'guard'     => 'admin',
        'user'      => Admin::class,
        'deletedBy' => null,
    ];

    protected $fillable = [
        'title',
        'content'
    ];

    protected $table = 'news';
}
