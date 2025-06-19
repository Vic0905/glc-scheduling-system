<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $user = User::find($id);
        $roles = Role::orderBy('name')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id); // Find the user

        $request->validate([
            'role_id' => 'required|exists:roles,id', // Validate role exists
        ]);

        // Assign the new role (remove old ones first)
        $user->syncRoles(Role::find($request->role_id));

        return redirect()->route('users.index')->with('success', 'User role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id); // Find the user
    
        // Optional: Remove all roles before deleting (if using Spatie)
        $user->roles()->detach(); 
    
        $user->delete(); // Delete the user
    
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
