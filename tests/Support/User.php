<?php

namespace BardanIO\SimpleId\Tests\Support;

use BardanIO\SimpleId\HasSimpleId;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasSimpleId;
    protected $table = 'users';
}