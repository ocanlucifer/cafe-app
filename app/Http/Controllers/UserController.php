<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $sortBy = $request->input('sort_by', 'name');
        $order = $request->input('order', 'asc');
        $perPage = $request->input('per_page', 10);

        // Mengambil data pengguna dengan filter, sorting, dan pagination
        $users = User::when($search, function ($query, $search) {
                    return $query->where('name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%")
                                 ->orWhere('username', 'like', "%{$search}%")
                                 ->orWhere('role', 'like', "%{$search}%");
                })
                ->orderBy($sortBy, $order)
                ->paginate($perPage);

        if ($request->ajax()) {
            // return response()->json(view('users.table', compact('users'))->render());
            return view('users.table', compact('users', 'search', 'sortBy', 'order', 'perPage'));
        }

        // return view('users.index', compact('users'));
        return view('users.index', compact('users', 'search', 'sortBy', 'order', 'perPage'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        return response()->json(['user' => $user]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            // 'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->username),
            'role' => $request->role ?? 'user',
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json(['success' => 'User created successfully!']);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found!']);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'role' => $request->role,
            'is_active' => $request->is_active,
        ]);

        return response()->json(['success' => 'User updated successfully!']);
    }


    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return response()->json(['success' => 'User deleted successfully!']);
        }

        return response()->json(['error' => 'User not found!']);
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active; // Toggle status
        $user->save();

        return response()->json(['error' => 'User status updated successfully.']);
        // return redirect()->back()->with('success', 'User status updated successfully.');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        // Generate a new password (you can customize this logic)
        $newPassword = $user->username; //Str::random(8);

        // Update the user's password
        $user->password = Hash::make($newPassword);
        $user->save();

        // Optionally, send an email to the user with the new password
        // Mail::to($user->email)->send(new ResetPasswordMail($newPassword));

        return response()->json([
            'success' => true,
            'message' => "Password for {$user->name} has been reset.",
            'new_password' => $newPassword, // Optional: include the new password in the response
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail(Auth::user()->id); ;

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();
        // $user->update([
        //     'password' => Hash::make($request->new_password),
        // ]);

        return back()->with('success', 'Password changed successfully.');
    }

}
