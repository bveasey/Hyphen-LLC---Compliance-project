<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function testCreatingUserResourceCreatesActivityLog(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('activity_log', [
           'description' => 'created',
           'subject_type' => 'App\Models\User',
           'event' => 'created',
       ]);
    }

    /**
     * @test
     * @return void
     */
    public function testUpdatingUserResourceCreatesActivityLog(): void
    {
        $user = User::factory()->create();

        $user->update(['name' => 'Steve Sunken']);

        $this->assertDatabaseHas('activity_log', [
            'description' => 'updated',
            'subject_type' => 'App\Models\User',
            'event' => 'updated',
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function testDeletingUserResourceCreatesActivityLog(): void
    {
        $user = User::factory()->create();

        $user->delete();

        $this->assertDatabaseHas('activity_log', [
            'description' => 'deleted',
            'subject_type' => 'App\Models\User',
            'event' => 'deleted',
        ]);
    }
}
