<?php

namespace Tests\Feature\ModelTests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function testUsersAreSoftDeleted(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', ['email' => $user->email]);

        $user->delete();

        $this->assertSoftDeleted($user);
    }
}
