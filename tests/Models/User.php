<?php

namespace RichanFongdasen\EloquentBlameableTest\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameableTest\Models\Extensions\AuthenticatableTrait;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class User extends Model implements Authenticatable
{
    use AuthenticatableTrait;
    use BlameableTrait;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password'
    ];
}
