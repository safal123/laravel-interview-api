<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        // Check if the user is authorized to view any customers
        Gate::authorize('viewAny', Customer::class);

        // query
        $query = Customer::query();
        /*
         * Note: This is a simple example, in a real-world application we can
         *      use third party packages like Spatie Query Builder
         */
        // Check if the include=contacts query parameter is present
        if (request()->has('include') && request()->query('include') === 'contacts') {
            $query
                ->with('contacts')
                ->withCount('contacts');
        }

        return CustomerResource::collection(
            $query
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        );
    }

    public function store(StoreCustomerRequest $request): CustomerResource
    {
        // Check if the user is authorized to create a customer
        Gate::authorize('create', Customer::class);
        $data = $request->validated();
        $customer = Customer::create($data);

        return new CustomerResource($customer);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        // Check if the user is authorized to delete the customer
        Gate::authorize('delete', $customer);

        // Delete the customer
        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
