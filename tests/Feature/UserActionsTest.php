<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_revoke_user_access()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('users.revoke', $user));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertNotNull($user->fresh()->blocked_at);
    }

    public function test_admin_can_logout_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        // Simulate a session
        DB::table('sessions')->insert([
            'id' => \Illuminate\Support\Str::random(40),
            'user_id' => $user->id,
            'payload' => 'test',
            'last_activity' => time(),
        ]);

        $response = $this->actingAs($admin)->post(route('users.logout', $user));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('sessions', ['user_id' => $user->id]);
    }
}
