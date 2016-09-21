<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;
    // seed需要关闭，正式使用请开启，保护密码
    // protected $hidden = ['password'];
    protected $fillable   = ['name', 'email', 'mobile'];
}
