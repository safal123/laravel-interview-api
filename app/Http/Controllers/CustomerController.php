<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    public function index()
    {
        // Check if the user is authorized to view any customers
        Gate::authorize('viewAny', Customer::class);

        // Build the query
        $query = Customer::query()
            ->orderBy('created_at', 'desc');
        /*
         * Note: This is a simple example, in a real-world application we can
         *      use third party packages like Spatie Query Builder
         */

        // check if the search query parameter is present
        if (request()->has('search')) {
            $search = request()->query('search');
            $query
                ->where('name', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
        }

        if (request()->has('category')) {
            $category = request()->query('category');
            $query->where('category', $category);
        }

        // Check if the include=contacts query parameter is present
        if (request()->has('include') && request()->query('include') === 'contacts') {
            $query
                ->with('contacts')
                ->withCount('contacts');
        }

        return CustomerResource::collection($query->paginate(10));
    }

    public function store(): CustomerResource
    {
        // Check if the user is authorized to create a customer
        Gate::authorize('create', Customer::class);

        // Validate the request
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            // Category can be Gold, Silver, or Bronze
            'category' => 'required|in:Gold,Silver,Bronze',
            'reference' => 'required|string|max:255',
            'start_date' => 'required|date',
        ]);

        // Create the customer
        $customer = Customer::create($data);

        return new CustomerResource($customer);
    }

    public function update(Customer $customer): CustomerResource
    {
        // Check if the user is authorized to update the customer
        Gate::authorize('update', $customer);

        // Validate the request
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            // Category can be Gold, Silver, or Bronze
            'category' => 'required|in:Gold,Silver,Bronze',
            'reference' => 'required|string|max:255',
            'start_date' => 'required|date',
        ]);

        // Update the customer
        $customer->update($data);

        return new CustomerResource($customer);
    }

    public function destroy(Customer $customer): \Illuminate\Http\JsonResponse
    {
        // Check if the user is authorized to delete the customer
        Gate::authorize('delete', $customer);

        // Delete the customer
        $customer->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
