<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_successfully(): void
    {
        $this->seed(); // To have roles (R01, R02, etc.)

        $userData = [
            'nom' => 'Doe',
            'prenom' => 'John',
            'email' => 'john@example.com',
            'telephone' => '12345678',
            'site_web' => 'https://example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'id_role' => 'R01',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Utilisateur créé avec succès',
                'data' => [
                    'nom' => 'Doe',
                    'prenom' => 'John',
                    'email' => 'john@example.com',
                    'id_role' => 'R01',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'nom' => 'Doe',
            'id_role' => 'R01',
        ]);
        
        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
    }

    public function test_registration_fails_missing_nom(): void
    {
        $userData = [
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nom']);
    }

    public function test_registration_fails_invalid_email(): void
    {
        $userData = [
            'nom' => 'Doe',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_registration_fails_existing_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $userData = [
            'nom' => 'Doe',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_registration_fails_password_mismatch(): void
    {
        $userData = [
            'nom' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_registration_fails_short_password(): void
    {
        $userData = [
            'nom' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_registration_fails_missing_id_role(): void
    {
        $userData = [
            'nom' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id_role']);
    }

    public function test_registration_fails_invalid_id_role(): void
    {
        $userData = [
            'nom' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'id_role' => 'INVALID_CODE',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id_role']);
    }
}
