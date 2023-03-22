<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->password = 'i-love-hyphen';

        $this->adminRole = Role::create(['name' => 'admin']);
        $this->userRole = Role::create(['name' => 'user']);

        $this->user = User::factory()
            ->password($this->password)
            ->create();

        $this->token = $this->user->createToken($this->user->name . '-token')->plainTextToken;
    }

    public function testCanGetAllUsers()
    {
        $user2 = User::factory()
            ->password('i-love-tailwind-css')
            ->create();

        $user3 = User::factory()
            ->password('i-love-tailwind-css')
            ->trashed()
            ->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('users.index'));

        $responseData = collect(json_decode($response->getContent())->data);
        $emails = $responseData->pluck('email');

        $this->assertEquals(2, $responseData->count());
        $this->assertContains($this->user->email, $emails);
        $this->assertContains($user2->email, $emails);
        $this->assertNotContains($user3->email, $emails);
    }

    public function testCanGetUser()
    {
        $user2 = User::factory()
            ->password('i-love-tailwind-css')
            ->create();

        $user2->assignRole($this->adminRole);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson(route('users.show', [$user2]));

        $responseData = collect(json_decode($response->getContent())->data);

        $this->assertEquals($user2->email, $responseData['email']);
        $this->assertEquals($user2->name, $responseData['name']);
        $this->assertEquals($this->adminRole->name, $responseData['roles'][0]->name);
    }

    public function testCanSoftDeleteAUser()
    {
        $user2 = User::factory()
            ->password('i-love-tailwind-css')
            ->create();

        $this->assertDatabaseHas('users', [
            'id' => $user2->getKey(),
            'email' => $user2->email,
        ]);

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->delete(route('users.destroy', [$user2]))
            ->assertStatus(204);

        $this->assertSoftDeleted('users', [
            'id' => $user2->getKey(),
            'email' => $user2->email,
        ]);
    }

    public function testCanCreateAUserWithRole()
    {
        $name = 'Bom Trady';
        $email = 'trady@pats.com';

        $this->assertDatabaseMissing('users', [
            'name' => $name,
            'email' => $email,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('users.store'), [
                'name' => $name,
                'email' => $email,
                'password' => '#GoPats#touchdown',
                'roles' => [$this->adminRole->getKey()],
            ]);

        $newUser = json_decode($response->getContent())->data;

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
        ]);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $this->adminRole->getKey(),
            'model_id' => $newUser->id,
        ]);
    }

    public function testCanUpdateAUserWithPassword()
    {
        $user2 = User::factory()
            ->password('i-love-tailwind-css')
            ->create();

        $user2->assignRole($this->userRole);

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->patch(route('users.update', $user2), [
                'name' => 'Sally Sailor',
                'email' => 'sailor@bay.com',
                'password' => '#GoPats#touchdown',
                'roles' => [$this->adminRole->getKey()],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user2->getKey(),
            'name' => 'Sally Sailor',
            'email' => 'sailor@bay.com',
        ]);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $this->adminRole->getKey(),
            'model_id' => $user2->getKey(),
        ]);

        $this->assertDatabaseMissing('model_has_roles', [
            'role_id' => $this->userRole->getKey(),
            'model_id' => $user2->getKey(),
        ]);
        $user2->refresh();

        $this->assertTrue(Hash::check('#GoPats#touchdown', $user2->password));
    }

    public function testCanUpdateAUserWithoutPassword()
    {
        $user2 = User::factory()
            ->password('i-love-tailwind-css')
            ->create();

        $user2->assignRole($this->userRole);

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->patch(route('users.update', $user2), [
                'name' => 'Sally Sailor',
                'email' => 'sailor@bay.com',
                'password' => '',
                'roles' => [$this->adminRole->getKey()],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user2->getKey(),
            'name' => 'Sally Sailor',
            'email' => 'sailor@bay.com',
        ]);

        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $this->adminRole->getKey(),
            'model_id' => $user2->getKey(),
        ]);

        $this->assertDatabaseMissing('model_has_roles', [
            'role_id' => $this->userRole->getKey(),
            'model_id' => $user2->getKey(),
        ]);
        $user2->refresh();

        $this->assertTrue(Hash::check('i-love-tailwind-css', $user2->password));
    }
}
