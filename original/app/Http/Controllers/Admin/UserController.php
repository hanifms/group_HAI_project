<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(): View
    {
        $users = User::with('role')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): View
    {
        // Load user's bookings with travel package and review
        $user->load(['bookings.travelPackage', 'bookings.review']);

        // Group bookings by status
        $pendingBookings = $user->bookings->where('status', 'pending');
        $ongoingBookings = $user->bookings->where('status', 'ongoing');
        $completedBookings = $user->bookings->where('status', 'completed');
        $reviews = $user->reviews()->with('booking.travelPackage')->get();

        return view('admin.users.show', compact(
            'user',
            'pendingBookings',
            'ongoingBookings',
            'completedBookings',
            'reviews'
        ));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Update the user attributes directly
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Save the changes to the database
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Check if user is trying to delete themselves
        if (Auth::id() === $user->id) {
            return back()->withErrors(['user' => 'You cannot delete your own account.']);
        }

        // Check if user is an admin
        if ($user->isAdmin()) {
            // For additional security, maybe require confirmation or special permissions
            // to delete an admin account
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Change the role of a specific user.
     */
    public function changeRole(Request $request, User $user)
    {
        // Validate the request
        $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        // Update user's role
        $user->role_id = $request->role_id;
        $user->save();

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User role updated successfully.');
    }
}
