<?php

namespace Tests\Feature\AccountLookup;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class Perimeter81AccountLookupTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken($this->user->name . '-token')->plainTextToken;

        $this->service = Service::create([
            'name' => 'Test p81 service',
            'service_slug' => 'perimeter_81',
            'base_url' => '',
            'token' => 'abc123',
        ]);
    }

    /**
     * @test
     */
    public function testCanLookupP81AccountWithActiveAccount()
    {
        $jsonResponse = json_encode([
            'data' => [
                [
                    'firstName' => 'Test',
                    'lastName' => 'User',
                    'email' => 'test@example.com',
                    'terminated' => false,
                ],
            ],
            'itemsTotal' => 1,
        ]);

        Http::fake(function ($request) use ($jsonResponse) {
            return Http::response($jsonResponse, 200);
        });

        Cache::put('perimeter81Token', 'abc123', now()->addDay());

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
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
    public function testCanLookupP81AccountWithInactiveAccount()
    {
        $jsonResponse = json_encode([
            'data' => [
                [
                    'firstName' => 'Test',
                    'lastName' => 'User',
                    'email' => 'test@example.com',
                    'terminated' => true,
                ],
            ],
            'itemsTotal' => 1,
        ]);

        Http::fake(function ($request) use ($jsonResponse) {
            return Http::response($jsonResponse, 200);
        });

        Cache::put('perimeter81Token', 'abc123', now()->addDay());

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
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
    public function testCanLookupP81AccountWithNoAccount()
    {
        $jsonResponse = json_encode([
            'data' => [],
            'itemsTotal' => 0,
        ]);

        Http::fake(function ($request) use ($jsonResponse) {
            return Http::response($jsonResponse, 200);
        });

        Cache::put('perimeter81Token', 'abc123', now()->addDay());

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson(route('services.accountLookup', [$this->service]), ['email' => 'test@example.com'])
            ->assertOk();

        $responseData = json_decode($response->getContent())->data;

        $this->assertEquals($this->service->name, $responseData->service);
        $this->assertEquals('', $responseData->name);
        $this->assertEquals('', $responseData->email);
        $this->assertEquals('Account not Found', $responseData->status);
    }
}
