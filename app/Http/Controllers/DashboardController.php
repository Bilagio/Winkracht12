<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Authentication middleware temporarily disabled
        // $this->middleware('auth');
    }

    /**
     * Show the appropriate dashboard based on user role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check()) {
            // If not logged in, show default dashboard
            return view('dashboard')->with('status', 'Welcome to the dashboard! (Auth temporarily disabled)');
        }

        $user = Auth::user();

        try {
            if ($user->isAdmin()) {
                return view('admin.dashboard');
            } elseif ($user->isInstructor()) {
                return view('instructor.dashboard');
            } else {
                return view('customer.home');
            }
        } catch (\Exception $e) {
            // Fallback to default dashboard if specific view is not found
            return view('dashboard')->with('status', 'Welcome to your dashboard!');
        }
    }
}
