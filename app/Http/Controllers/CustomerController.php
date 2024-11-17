<?php

// app/Http/Controllers/CustomerController.php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'name');
        $order = $request->input('order', 'asc');
        $perPage = $request->input('per_page', 10);

        $customers = Customer::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%$search%")
                            ->orWhere('contact', 'like', "%$search%")
                            ->orWhere('address', 'like', "%$search%");
            })
            ->orderBy($sortBy, $order)
            ->paginate($perPage);

        if ($request->ajax()) {
            return view('customers.table', compact('customers', 'search', 'sortBy', 'order', 'perPage'))->render();
        }

        return view('customers.index', compact('customers', 'search', 'sortBy', 'order', 'perPage'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);
        Customer::create($request->all());
        // return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
        return response()->json(['message' => 'Customer created successfully']);
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);
        $customer->update($request->all());
        // return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
        return response()->json(['message' => 'Customer updated successfully']);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        // return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
        return response()->json(['message' => 'Customer deleted successfully']);
    }

    public function toggleActive(Customer $customer)
    {
        // Toggle the active status
        $customer->active = !$customer->active;
        $customer->save();

        // return redirect()->route('customers.index');
        return response()->json(['message' => 'Customer status updated successfully']);
    }

}
