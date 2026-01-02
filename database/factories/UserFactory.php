<?php

namespace ArieTimmerman\Laravel\SCIMServer\Database\Factories;

use ArieTimmerman\Laravel\SCIMServer\Tests\Model\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->email(),
            'name' => $this->faker->name(),
            'password' => 'test',
        ];
    }
}
