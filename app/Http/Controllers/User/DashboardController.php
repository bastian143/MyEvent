<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $registeredEvents = $user->eventRegistrations()->whereHas('event')->with('event.category')->latest('registered_at')->get();
        $teams = $user->teamMembers()->whereHas('team.event')->with('team.event')->latest()->get();
        $pendingInvites = \App\Models\TeamJoinRequest::where('user_id', $user->id)
            ->where('type', 'invite')
            ->where('status', 'pending')
            ->with('team.event')
            ->get();
        
        // 1. Activity Summary Stats
        $stats = [
            'registered' => $registeredEvents->count(),
            'completed' => $registeredEvents->filter(fn($reg) => \Carbon\Carbon::parse($reg->event->end_date)->isPast())->count(),
            'certificates' => \App\Models\Certificate::where('user_id', $user->id)->count(),
        ];

        // 2. Upcoming Deadlines/Events
        $upcomingEvents = $registeredEvents->filter(fn($reg) => \Carbon\Carbon::parse($reg->event->start_date)->isFuture())->take(5);

        // 3. Saved Events
        $savedEvents = $user->bookmarks()->whereHas('event')->with('event.category')->get()->pluck('event');

        // 4. Team Recommendations (Dummy / Latest teams needing members)
        $recommendedTeams = \App\Models\Team::where('leader_id', '!=', $user->id)
                                            ->has('event')
                                            ->with('event', 'leader')
                                            ->latest()
                                            ->take(3)
                                            ->get();

        // 5. Event Recommendations (Latest events not joined by user)
        $joinedEventIds = $registeredEvents->pluck('event_id')->toArray();
        $recommendedEvents = \App\Models\Event::whereNotIn('id', $joinedEventIds)
                                              ->where('status', 'approved')
                                              ->with('category')
                                              ->inRandomOrder()
                                              ->take(4)
                                              ->get();
        
        return view('user.dashboard', compact(
            'registeredEvents', 'teams', 'pendingInvites',
            'stats', 'upcomingEvents', 'savedEvents', 'recommendedTeams', 'recommendedEvents'
        ));
    }
}
