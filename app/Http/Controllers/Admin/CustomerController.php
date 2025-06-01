<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $customers = $query->orderBy('name')->paginate(10);
        
        return view('admin.customers.index', compact('customers'));
    }
    
    /**
     * Show the form for creating a new customer
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.customers.create');
    }
    
    /**
     * Store a newly created customer
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => [
                'nullable', 
                'date', 
                'before_or_equal:'.now()->subYears(18)->format('Y-m-d'),
                'after_or_equal:1950-01-01'
            ],
            'bsn' => ['nullable', 'string', 'max:9'],
            'mobile' => ['nullable', 'string', 'max:15'],
        ]);
        
        // Generate password if not provided
        if (empty($validated['password'])) {
            $validated['password'] = Str::random(12);
            $sendPassword = true;
        }
        
        // Always hash the password
        $validated['password'] = Hash::make($validated['password']);
        
        // Set role to customer
        $validated['role'] = 'customer';
        
        // Set default notification preferences
        $validated['notification_preferences'] = User::getDefaultNotificationPreferences();
        
        $customer = User::create($validated);
        
        // Send password if auto-generated
        if (isset($sendPassword)) {
            // In a real app, you would send an email with the password here
            // For now, we'll just flash it to the session
            return redirect()->route('admin.customers.index')
                ->with('status', 'Customer created successfully. Auto-generated password: ' . $validated['password']);
        }
        
        return redirect()->route('admin.customers.index')
            ->with('status', 'Customer created successfully.');
    }
    
    /**
     * Display the specified customer
     *
     * @param  \App\Models\User  $customer
     * @return \Illuminate\View\View
     */
    public function show(User $customer)
    {
        // Make sure we're only showing customers
        if ($customer->role !== 'customer') {
            return redirect()->route('admin.customers.index')
                ->with('error', 'The requested user is not a customer.');
        }
        
        // Get customer's reservations for the lessons tab
        $reservations = $customer->reservations()->latest()->get();
        
        return view('admin.customers.show', compact('customer', 'reservations'));
    }
    
    /**
     * Show the form for editing the specified customer
     *
     * @param  \App\Models\User  $customer
     * @return \Illuminate\View\View
     */
    public function edit(User $customer)
    {
        // Make sure we're only editing customers
        if ($customer->role !== 'customer') {
            return redirect()->route('admin.customers.index')
                ->with('error', 'The requested user is not a customer.');
        }
        
        return view('admin.customers.edit', compact('customer'));
    }
    
    /**
     * Update the specified customer
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $customer)
    {
        // Make sure we're only updating customers
        if ($customer->role !== 'customer') {
            return redirect()->route('admin.customers.index')
                ->with('error', 'The requested user is not a customer.');
        }
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($customer->id)],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => [
                'nullable', 
                'date', 
                'before_or_equal:'.now()->subYears(18)->format('Y-m-d'),
                'after_or_equal:1950-01-01'
            ],
            'bsn' => ['nullable', 'string', 'max:9'],
            'mobile' => ['nullable', 'string', 'max:15'],
        ]);
        
        // Only update password if provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        
        $customer->update($validated);
        
        return redirect()->route('admin.customers.edit', $customer)
            ->with('status', 'Customer updated successfully.');
    }
    
    /**
     * Show confirmation before deleting the customer
     * 
     * @param  \App\Models\User  $customer
     * @return \Illuminate\View\View
     */
    public function confirmDelete(User $customer)
    {
        // Make sure we're only deleting customers
        if ($customer->role !== 'customer') {
            return redirect()->route('admin.customers.index')
                ->with('error', 'The requested user is not a customer.');
        }
        
        return view('admin.customers.confirm-delete', compact('customer'));
    }
    
    /**
     * Remove the specified customer
     *
     * @param  \App\Models\User  $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $customer)
    {
        // Make sure we're only deleting customers
        if ($customer->role !== 'customer') {
            return redirect()->route('admin.customers.index')
                ->with('error', 'The requested user is not a customer.');
        }
        
        // Delete customer and their related data
        $customer->delete();
        
        return redirect()->route('admin.customers.index')
            ->with('status', 'Customer deleted successfully.');
    }
}
