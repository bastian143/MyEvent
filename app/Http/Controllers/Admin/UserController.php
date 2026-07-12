<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(10);
        $roles = Role::all();
        $organizerRequests = \App\Models\OrganizerRequest::with('user')->where('status', 'pending')->latest()->get();
        return view('admin.users.index', compact('users', 'roles', 'organizerRequests'));
    }

    public function handleOrganizerRequest(Request $request, \App\Models\OrganizerRequest $organizerRequest)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        
        $organizerRequest->update(['status' => $request->status]);

        if ($request->status === 'approved') {
            if ($organizerRequest->type === 'upgrade') {
                $organizerRequest->user->syncRoles(['organizer']);
            } elseif ($organizerRequest->type === 'downgrade') {
                $organizerRequest->user->syncRoles(['user']);
                
                // Delete all past events created by this user
                \App\Models\Event::where('creator_id', $organizerRequest->user_id)->delete();
            }
        }

        return back()->with('success', 'Organizer request ' . $request->status);
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|exists:roles,name']);
        
        $user->syncRoles([$request->role]);

        return back()->with('success', 'User role updated successfully.');
    }

    public function toggleBlock(User $user)
    {
        $user->update(['is_blocked' => !$user->is_blocked]);
        
        $status = $user->is_blocked ? 'blocked' : 'unblocked';
        return back()->with('success', "User $status successfully.");
    }
}
