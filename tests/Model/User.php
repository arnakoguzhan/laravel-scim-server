<?php

namespace ArieTimmerman\Laravel\SCIMServer\Tests\Model;

class User extends \Illuminate\Foundation\Auth\User
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected static function newFactory()
    {
        return \ArieTimmerman\Laravel\SCIMServer\Database\Factories\UserFactory::new();
    }
}
