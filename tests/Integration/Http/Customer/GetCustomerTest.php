<?php

namespace Tests\Feature\Integration\Http\Customer;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Suite\Traits\CreateUsers;
use Tests\TestCase;

class GetCustomerTest extends TestCase
{
    use RefreshDatabase, CreateUsers;

    private string $customersRoute = 'customers.index';

    /**
     * @test
     */
    public function guest_cannot_access_customer_list(): void
    {
        $this->getJson(route($this->customersRoute))
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function non_admin_user_cannot_access_customer_list(): void
    {
        $this->actingAs($this->createUser())
            ->getJson(route($this->customersRoute))
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function admin_user_can_access_customer_list(): void
    {
        $response = $this->actingAs($this->createAdminUser())
            ->getJson(route($this->customersRoute))
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function admin_user_can_view_all_customers(): void
    {
        Customer::factory(10)->create();

        $response = $this->actingAs($this->createAdminUser())
            ->getJson(route($this->customersRoute));

        $this->assertCount(10, $response->json('data'));
    }

    /**
     * @test
     */
    public function admin_user_must_include_contacts_query_to_load_customer_contacts(): void
    {
        Customer::factory(10)
            ->hasContacts(5)
            ->create();

        $response = $this->actingAs($this->createAdminUser())
            ->getJson(route($this->customersRoute));

        $this->assertCount(10, $response->json('data'));
        $this->assertArrayNotHasKey('contacts', $response->json('data.0'));
    }

    /**
     * @test
     */
    public function admin_user_can_view_customers_with_contacts(): void
    {
        Customer::factory(10)
            ->hasContacts(5)
            ->create();

        $response = $this->actingAs($this->createAdminUser())
            ->getJson(route($this->customersRoute) . '?include=contacts');

        $this->assertCount(10, $response->json('data'));
        $this->assertCount(5, $response->json('data.0.contacts'));
    }

    /**
     * @test
     */
    public function contacts_are_only_loaded_if_requested_in_query(): void
    {
        Customer::factory(10)
            ->hasContacts(5)
            ->create();

        $response = $this->actingAs($this->createAdminUser())
            ->getJson(route($this->customersRoute) . '?include=contacts');

        $this->assertCount(10, $response->json('data'));
        $this->assertCount(5, $response->json('data.0.contacts'));
    }

    /**
     * @test
     */
    public function admin_user_can_view_paginated_customers(): void
    {
        Customer::factory(20)->create();

        $response = $this->actingAs($this->createAdminUser())
            ->getJson(route($this->customersRoute));

        $this->assertCount(10, $response->json('data'));
        $this->assertNotNull($response->json('links'));
    }

    /**
     * @test
     */
    public function admin_user_can_view_paginated_customers_and_can_visit_next_page(): void
    {
        Customer::factory(20)->create();

        $response = $this->actingAs($this->createAdminUser())
            ->getJson(route($this->customersRoute));

        $this->assertCount(10, $response->json('data'));
        $this->assertNotNull($response->json('links.next'));

        $response = $this->actingAs($this->createAdminUser())
            ->getJson($response->json('links.next'));

        $this->assertCount(10, $response->json('data'));
        $this->assertNotNull($response->json('links.prev'));
    }
}
