<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the customer profile form
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('customer.profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the customer profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'mobile' => ['required', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
