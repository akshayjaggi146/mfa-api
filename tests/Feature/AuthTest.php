<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_user_cannot_register_with_existing_email()
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_request_mfa_token()
    {
        Mail::fake();
        $user = User::factory()->create(['password' => Hash::make('password123')]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $this->assertNotNull($user->fresh()->mfa_token);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }

    public function test_user_can_verify_mfa_token()
    {
        $user = User::factory()->create(['mfa_token' => '123456']);

        $response = $this->postJson('/api/verify-mfa', [
            'user_id' => $user->id,
            'mfa_token' => '123456',
        ]);

        $response->assertStatus(200);
        $this->assertNull($user->fresh()->mfa_token);
    }

    public function authenticated_user_can_logout()
    {
        
        Passport::actingAs(User::factory()->create());

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200);
    }
    public function test_user_cannot_verify_invalid_mfa_token()
    {
        $user = User::factory()->create(['mfa_token' => '123456']);

        $response = $this->postJson('/api/verify-mfa', [
            'user_id' => $user->id,
            'mfa_token' => 'wrongtoken',
        ]);

        $response->assertStatus(401);
    }

}
