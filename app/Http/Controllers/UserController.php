<?php

namespace App\Http\Controllers;

use App\Mail\UserCredentialsMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|max:20|unique:users',
            'whatsapp_number' => 'nullable|string|max:20',
            'area' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
        ]);

        // Generate username from email or name
        $username = $this->generateUniqueUsername($request->name, $request->email);

        // Generate random password
        $password = Str::random(12);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $username,
            'mobile' => $request->mobile,
            'whatsapp_number' => $request->whatsapp_number,
            'area' => $request->area,
            'address' => $request->address,
            'country' => $request->country,
            'state' => $request->state,
            'password' => Hash::make($password),
        ]);

        // Send credentials email
        Mail::to($user->email)->send(new UserCredentialsMail($user, $username, $password));

        return redirect()->route('users.show', $user)->with('success', 'User created successfully. Credentials have been sent to their email.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'mobile' => 'required|string|max:20|unique:users,mobile,' . $user->id,
            'whatsapp_number' => 'nullable|string|max:20',
            'area' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
        ]);

        $user->update($request->only([
            'name',
            'email',
            'mobile',
            'whatsapp_number',
            'area',
            'address',
            'country',
            'state',
        ]));

        return redirect()->route('users.show', $user)->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Generate a unique username from name and email.
     */
    private function generateUniqueUsername($name, $email)
    {
        // Try to create username from first name and last name
        $parts = explode(' ', trim($name));
        $username = strtolower($parts[0]);

        if (count($parts) > 1) {
            $username = strtolower($parts[0] . $parts[count($parts) - 1]);
        }

        // If username already exists, append a random number
        $originalUsername = $username;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }

        return $username;
    }
}
