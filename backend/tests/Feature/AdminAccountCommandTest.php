<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccountCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_existing_account_can_be_promoted_from_the_console(): void
    {
        $user = User::factory()->create(['email' => 'editor@example.test', 'role' => 'subscriber']);

        $this->artisan('admin:promote', [
            'email' => $user->email,
            '--yes' => true,
        ])
            ->expectsOutputToContain('est maintenant administrateur')
            ->assertExitCode(0);

        $this->assertSame('admin', $user->fresh()->role);
    }

    public function test_promotion_fails_for_an_unknown_account(): void
    {
        $this->artisan('admin:promote', ['email' => 'missing@example.test'])
            ->expectsOutputToContain('Aucun compte ne correspond')
            ->assertExitCode(1);
    }
}
