<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    use RefreshDatabase, withFaker;

    public function setup(): void
    {
        parent::setup();
        $this->password = 'i-love-hyphen';

        $this->user = User::factory()
            ->password($this->password)
            ->create();
    }

    /**
     * @test
     * @return void
     */
    public function testUserCanLogin(): void
    {
        $response = $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => $this->password,
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeText(__('messages.login-successful'));
        $response->assertJsonStructure([
            'message',
            'data' => [
                'user',
                'token',
            ],
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function testUserCanLogout(): void
    {
        $token = $this->user->createToken($this->user->name . '-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson(route('logout'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSeeText(__('messages.logout-successful'));
    }

    /**
     * @test
     * @return void
     */
    public function testUserCanNotLogin(): void
    {
        $response = $this->postJson(route('login'), [
            'email' => $this->faker->unique()->email(),
            'password' => $this->password,
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertSeeText(__('messages.unauthenticated'));
    }

    /**
     * @test
     * @return void
     */
    public function testGetUser(): void
    {
        $token = $this->user->createToken($this->user->name . '-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson(route('user.show'))
            ->assertStatus(Response::HTTP_OK);

        $data = json_decode($response->getContent())->data;

        $this->assertEquals($this->user->name, $data->name);
        $this->assertEquals($this->user->email, $data->email);
    }
}
