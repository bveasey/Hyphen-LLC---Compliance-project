<?php

namespace Tests\Feature\AccountLookup;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SlackAccountLookupTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = Service::create([
            'name' => 'Test Slack service',
            'service_slug' => 'slack',
            'base_url' => '',
            'token' => 'abc123',
        ]);
    }

    /**
     * @test
     */
    public function testNonAuthorizedUserGetsUnauthorized(): void
    {
        $token = Crypt::encrypt('abc123');

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson(route('services.accountLookup', [$this->service]), ['email' => 'test@example.com'])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function testCanLookupSlackAccountWithActiveAccount()
    {
        $user = User::factory()->create();
        $token = $user->createToken($user->name . '-token')->plainTextToken;

        $jsonResponse = json_encode([
            'ok' => true,
            'user' => [
                'name' => 'Test User',
                'deleted' => false,
                'profile' => [
                    'email' => 'test@example.com',
                ],
            ],
        ]);

        Http::fake(function ($request) use ($jsonResponse) {
            return Http::response($jsonResponse, 200);
        });

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson(route('services.accountLookup', [$this->service]), ['email' => 'test@example.com'])
            ->assertOk();

        $responseData = json_decode($response->getContent())->data;

        $this->assertEquals($this->service->name, $responseData->service);
        $this->assertEquals('Test User', $responseData->name);
        $this->assertEquals('test@example.com', $responseData->email);
        $this->assertEquals('Active', $responseData->status);
    }

    /**
     * @test
     */
    public function testCanLookupSlackAccountWithInactiveAccount()
    {
        $user = User::factory()->create();
        $token = $user->createToken($user->name . '-token')->plainTextToken;

        $jsonResponse = json_encode([
            'ok' => true,
            'user' => [
                'name' => 'Test User',
                'deleted' => true,
                'profile' => [
                    'email' => 'test@example.com',
                ],
            ],
        ]);

        Http::fake(function ($request) use ($jsonResponse) {
            return Http::response($jsonResponse, 200);
        });

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson(route('services.accountLookup', [$this->service]), ['email' => 'test@example.com'])
            ->assertOk();

        $responseData = json_decode($response->getContent())->data;

        $this->assertEquals($this->service->name, $responseData->service);
        $this->assertEquals('Test User', $responseData->name);
        $this->assertEquals('test@example.com', $responseData->email);
        $this->assertEquals('Inactive', $responseData->status);
    }

    /**
     * @test
     */
    public function testCanLookupSlackAccountWithNoAccount()
    {
        $user = User::factory()->create();
        $token = $user->createToken($user->name . '-token')->plainTextToken;

        $jsonResponse = json_encode([
            'ok' => false,
            'error' => 'users_not_found',
        ]);

        Http::fake(function ($request) use ($jsonResponse) {
            return Http::response($jsonResponse, 200);
        });

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson(route('services.accountLookup', [$this->service]), ['email' => 'test@example.com'])
            ->assertOk();

        $responseData = json_decode($response->getContent())->data;

        $this->assertEquals($this->service->name, $responseData->service);
        $this->assertEquals('', $responseData->name);
        $this->assertEquals('', $responseData->email);
        $this->assertEquals('Account not Found', $responseData->status);
    }
}
