<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->token = $this->user->createToken($this->user->name . '-token')->plainTextToken;
    }

    public function testCanGetAllRoles()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('role.index'));

        $data = collect(json_decode($response->getContent())->data);

        $this->assertEquals(2, $data->count());
        $this->assertContains('admin', $data->pluck('name'));
        $this->assertContains('user', $data->pluck('name'));
    }
}
