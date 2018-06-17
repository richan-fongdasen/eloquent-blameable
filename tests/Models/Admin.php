<?php

namespace RichanFongdasen\EloquentBlameableTest\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameableTest\Models\Extensions\AuthenticatableTrait;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class Admin extends Model implements Authenticatable
{
    use AuthenticatableTrait;
    use BlameableTrait;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    public function blameable()
    {
        return [
            'guard' => 'admin',
            'user' => self::class
        ];
    }
}
