<?php

namespace AchrafBardan\SimpleId\Tests\Support;

use AchrafBardan\SimpleId\HasSimpleId;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasSimpleId;
    protected $table = 'users';
}