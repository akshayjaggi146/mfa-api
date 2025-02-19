<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use Laravel\Passport\Passport;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_create_customer()
    {
        Passport::actingAs(User::factory()->create());

        $response = $this->postJson('/api/customers', [
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'phone' => '1234567890',
            'address' => 'Test Address',
        ]);

        $response->assertStatus(201)->assertJson([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'phone' => '1234567890',
            'address' => 'Test Address',
        ]);

        $this->assertDatabaseHas('customers', [
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'phone' => '1234567890',
            'address' => 'Test Address',
        ]);;
    }

    /** @test */
    public function user_can_view_all_customers()
    {
        Passport::actingAs(User::factory()->create());
        Customer::factory()->count(3)->create();

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function authenticated_user_can_update_customer()
    {
        Passport::actingAs(User::factory()->create());

        $customer = Customer::factory()->create();

        $response = $this->putJson("/api/customers/{$customer->id}", [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '9876543210',
            'address' => 'Updated Address',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                    'name' => 'Updated Name',
                    'email' => 'updated@example.com',
                    'phone' => '9876543210',
                    'address' => 'Updated Address',
                ]);
    }

    /** @test */
    public function authenticated_user_can_delete_customer()
    {
        Passport::actingAs(User::factory()->create());

        $customer = Customer::factory()->create();

        $response = $this->deleteJson("/api/customers/{$customer->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Customer deleted']);
    }
}

