<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // TEMPORARILY DISABLED MIDDLEWARE
        // $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.users', compact('users'));
    }

    /**
     * Update the specified user's role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'role' => 'required|in:customer,instructor',
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->route('admin.users')
            ->with('status', 'User role updated successfully.');
    }
}
