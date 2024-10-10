<?php

namespace Tests\Integration\Http\Customer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Suite\Traits\CreateUsers;
use Tests\TestCase;

class StoreCustomerTest extends TestCase
{
    use RefreshDatabase, CreateUsers;

    public string $urlPath = 'customers.store';

    /**
     * @test
     */
    public function guest_user_cannot_create_customer(): void
    {
        $this->postJson(route($this->urlPath))
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function non_admin_user_cannot_create_customer(): void
    {
        $this->actingAs($this->createUser())
            ->getJson(route($this->urlPath))
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function authenticated_admin_user_can_create_customer(): void
    {
        $response = $this->actingAs($this->createAdminUser())
            ->postJson(route($this->urlPath), [
                'name' => 'John Doe',
                'description' => 'This is a test description',
                'category' => 'Gold',
                'reference' => 'REF-123',
                'start_date' => '2024-10-10',
            ])
            ->assertStatus(201);
    }
}
