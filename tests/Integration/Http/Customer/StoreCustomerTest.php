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

    /**
     * @test
     * @dataProvider customerDataProvider
     */
    public function customer_data_must_be_valid(array $payload, int $expectedStatus): void
    {
        $this->actingAs($this->createAdminUser())
            ->postJson(route($this->urlPath), $payload)
            ->assertStatus($expectedStatus);
    }

    /**
     * Data provider for customer_data_must_be_valid test.
     *
     * @return array[]
     */
    public static function customerDataProvider(): array
    {
        return [
            'Valid data' => [
                [
                    'name' => 'John Doe',
                    'description' => 'This is a test description',
                    'category' => 'Gold',
                    'reference' => 'REF-123',
                    'start_date' => '2024-10-10',
                ],
                201
            ],
            'Invalid category' => [
                [
                    'name' => 'John Doe',
                    'description' => 'This is a test description',
                    'category' => 'Invalid Category',
                    'reference' => 'REF-123',
                    'start_date' => '2024-10-10',
                ],
                422
            ],
            'Missing start_date' => [
                [
                    'name' => 'John Doe',
                    'description' => 'This is a test description',
                    'category' => 'Gold',
                    'reference' => 'REF-123',
                ],
                422
            ],
            'Invalid start_date' => [
                [
                    'name' => 'John Doe',
                    'description' => 'This is a test description',
                    'category' => 'Gold',
                    'reference' => 'REF-123',
                    'start_date' => 'Invalid Date',
                ],
                422
            ],
            'Empty name' => [
                [
                    'name' => '',
                    'description' => 'This is a test description',
                    'category' => 'Gold',
                    'reference' => 'REF-123',
                    'start_date' => '2024-10-10',
                ],
                422
            ],
            'Empty description' => [
                [
                    'name' => 'John Doe',
                    'description' => '',
                    'category' => 'Gold',
                    'reference' => 'REF-123',
                    'start_date' => '2024-10-10',
                ],
                422
            ],
        ];
    }
}
