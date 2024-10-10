<?php

namespace Tests\Integration\Http\Customer;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Suite\Traits\CreateUsers;
use Tests\TestCase;

class UpdateCustomerTest extends TestCase
{
    use RefreshDatabase, CreateUsers;

    private string $customersRoute = 'customers.update';

    /**
     * @test
     */
    public function guest_cannot_update_customer(): void
    {
        $customer = Customer::factory()->create();
        $this->putJson(route($this->customersRoute, ['customer' => $customer]))
            ->assertStatus(401);
    }



    /**
     * @test
     */
    public function admin_user_can_update_customer(): void
    {
        $customer = Customer::factory()->create([
            'name' => 'Jane Doe',
            'description' => 'This is a test description',
            'category' => 'Silver',
            'reference' => 'REF-123',
            'start_date' => '2024-10-10',
        ]);
        $response = $this->actingAs($this->createAdminUser())
            ->putJson(route($this->customersRoute, ['customer' => $customer]), [
                'name' => 'John Doe',
                'description' => 'This is a test description for John Doe',
                'category' => 'Gold',
                'reference' => 'REF-456',
                'start_date' => '2024-10-10',
            ])
            ->assertStatus(200);

        $this->assertEquals('John Doe', $response->json('data.name'));
        $this->assertEquals('This is a test description for John Doe', $response->json('data.description'));
        $this->assertEquals('Gold', $response->json('data.category'));
        $this->assertEquals('REF-456', $response->json('data.reference'));
        $this->assertEquals('2024-10-10', $response->json('data.start_date'));
    }


}
