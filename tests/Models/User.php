<?php

namespace RichanFongdasen\EloquentBlameableTest\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use RichanFongdasen\EloquentBlameableTest\Models\Extensions\AuthenticatableTrait;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class User extends Model implements Authenticatable
{
    use AuthenticatableTrait;
    
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    public function blameable()
    {
        return self::class;
    }
}
