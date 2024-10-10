<?php

namespace Tests\Suite\Traits;

use App\Models\User;

trait CreateUsers
{
    public function createAdminUser()
    {
        return User::factory()->create([
            'role' => User::ROLE_ADMIN
        ]);
    }

    public function createUser()
    {
        return User::factory()->create();
    }
}
