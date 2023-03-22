<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test you can get the services.
     * @test
     * @return void
     */
    public function testCanGetServices()
    {
        $user = User::factory()->create();
        $token = $user->createToken($user->name . '-token')->plainTextToken;
        Service::create([
            'name' => 'test service 1',
            'service_slug' => 'slack',
            'base_url' => '',
            'token' => 'abc123',
        ]);


        Service::create([
            'name' => 'test service 2',
            'service_slug' => 'perimeter_81',
            'base_url' => '',
            'token' => 'xyz987',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson(route('services.index'))
            ->assertOk();

        $responseData = json_decode($response->getContent())->data;

        $this->assertEquals('test service 1', $responseData[0]->name);
        $this->assertEquals('test service 2', $responseData[1]->name);
    }
}
