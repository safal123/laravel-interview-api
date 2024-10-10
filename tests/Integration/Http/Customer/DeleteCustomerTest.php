<?php

namespace Tests\Integration\Http\Customer;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Suite\Traits\CreateUsers;
use Tests\TestCase;

class DeleteCustomerTest extends TestCase
{
    use RefreshDatabase, CreateUsers;

    private string $customersRoute = 'customers.update';

    /**
     * @test
     */
    public function guest_cannot_delete_customer(): void
    {
        $customer = Customer::factory()->create();
        $this->deleteJson(route($this->customersRoute, ['customer' => $customer]))
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function admin_user_can_delete_customer(): void
    {
        $customer = Customer::factory()->create([
            'name' => 'Jane Doe',
            'description' => 'This is a test description',
            'category' => 'Silver',
            'reference' => 'REF-123',
            'start_date' => '2024-10-10',
        ]);
        $response = $this->actingAs($this->createAdminUser())
            ->deleteJson(route($this->customersRoute, ['customer' => $customer]))
            ->assertStatus(204);
    }
}
