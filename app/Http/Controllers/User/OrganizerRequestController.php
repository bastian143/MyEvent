<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\OrganizerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizerRequestController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $isOrganizer = $user->hasRole('organizer');

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $existing = OrganizerRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            return back()->with('error', 'You already have a pending request.');
        }

        if ($isOrganizer) {
            // Downgrade Request
            // Check if there are any ongoing events created by this user
            $hasOngoingEvents = \App\Models\Event::where('creator_id', $user->id)
                ->where('end_date', '>', now())
                ->exists();

            if ($hasOngoingEvents) {
                return back()->with('error', 'You cannot request to downgrade while you have ongoing events.');
            }

            OrganizerRequest::create([
                'user_id' => $user->id,
                'type' => 'downgrade',
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            return back()->with('success', 'Your request to downgrade to a regular user has been submitted.');
        } else {
            // Upgrade Request
            if ($user->hasRole('admin')) {
                return back()->with('error', 'Admins cannot request to become organizers.');
            }

            OrganizerRequest::create([
                'user_id' => $user->id,
                'type' => 'upgrade',
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            return back()->with('success', 'Your request to become an organizer has been submitted.');
        }
    }
}
