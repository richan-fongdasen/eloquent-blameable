<?php

namespace RichanFongdasen\EloquentBlameableTest\Supports\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameableTest\Supports\Models\Extensions\AuthenticatableTrait;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class User extends Model implements Authenticatable
{
    use AuthenticatableTrait;
    use BlameableTrait;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password'
    ];
}
