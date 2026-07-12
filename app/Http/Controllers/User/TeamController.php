<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Team;
use App\Models\TeamJoinRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index(Event $event)
    {
        if ($event->registration_type !== 'team') {
            return back()->with('error', 'This event does not support teams.');
        }

        $teams = $event->teams()->with('leader')->get();
        return view('user.teams.index', compact('event', 'teams'));
    }

    public function store(Request $request, Event $event)
    {
        $request->validate(['name' => 'required|string|max:255']);

        // Check if user is registered for the event
        $isRegistered = \App\Models\EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->exists();

        if (!$isRegistered) {
            return redirect()->route('front.events.show', $event->slug)
                ->with('error', 'You must register for this event before you can create a team.');
        }

        $team = Team::create([
            'event_id' => $event->id,
            'leader_id' => Auth::id(),
            'name' => $request->name,
            'status' => 'approved'
        ]);

        // Leader is automatically a member
        $team->members()->create([
            'user_id' => Auth::id(),
            'status' => 'joined'
        ]);

        Auth::user()->notify(new \App\Notifications\ActivityNotification(
            "You have created the team '{$team->name}' for event '{$event->title}'.",
            route('user.teams.show', $team->id)
        ));

        return back()->with('success', 'Team created successfully!');
    }

    public function joinRequest(Team $team)
    {
        // Check if user is registered for the event
        $isRegistered = \App\Models\EventRegistration::where('event_id', $team->event_id)
            ->where('user_id', Auth::id())
            ->exists();

        if (!$isRegistered) {
            return redirect()->route('front.events.show', $team->event->slug)
                ->with('error', 'You must register for this event before you can request to join a team.');
        }

        // Check if team is full
        $teamSizeLimit = $team->event->team_size;
        if ($teamSizeLimit && $team->members()->where('status', 'joined')->count() >= $teamSizeLimit) {
            return back()->with('error', 'This team is already full.');
        }

        $existing = TeamJoinRequest::where('team_id', $team->id)
            ->where('user_id', Auth::id())
            ->where('type', 'request')
            ->exists();
        
        if ($existing) {
            return back()->with('error', 'You have already requested to join this team.');
        }

        $joinRequest = TeamJoinRequest::create([
            'team_id' => $team->id,
            'user_id' => Auth::id(),
            'type' => 'request',
            'status' => 'pending'
        ]);

        $team->leader->notify(new \App\Notifications\TeamJoinRequestNotification($joinRequest));

        return back()->with('success', 'Request sent to team leader!');
    }

    public function invite(Request $request, Team $team)
    {
        if ($team->leader_id !== Auth::id()) {
            return back()->with('error', 'Only the team leader can invite members.');
        }

        $request->validate(['email' => 'required|email|exists:users,email']);

        $userToInvite = \App\Models\User::where('email', $request->email)->first();

        if ($userToInvite->id === Auth::id()) {
            return back()->with('error', 'You cannot invite yourself.');
        }

        // The user doesn't need to be registered yet to receive the invite,
        // they will be forced to register when they try to accept it.

        // Check if team is full
        $teamSizeLimit = $team->event->team_size;
        if ($teamSizeLimit && $team->members()->where('status', 'joined')->count() >= $teamSizeLimit) {
            return back()->with('error', 'This team is already full.');
        }

        $existingMember = $team->members()->where('user_id', $userToInvite->id)->where('status', 'joined')->exists();
        if ($existingMember) {
            return back()->with('error', 'User is already a member of this team.');
        }

        $existingInvite = TeamJoinRequest::where('team_id', $team->id)
            ->where('user_id', $userToInvite->id)
            ->where('status', 'pending')
            ->exists();

        if ($existingInvite) {
            return back()->with('error', 'User already has a pending request or invitation.');
        }

        $invitation = TeamJoinRequest::create([
            'team_id' => $team->id,
            'user_id' => $userToInvite->id,
            'type' => 'invite',
            'status' => 'pending'
        ]);

        $userToInvite->notify(new \App\Notifications\TeamInvitationNotification($invitation));

        return back()->with('success', 'Invitation sent successfully!');
    }

    public function respondInvite(TeamJoinRequest $invite, Request $request)
    {
        if ($invite->user_id !== Auth::id() || $invite->type !== 'invite') {
            abort(403);
        }

        $request->validate(['action' => 'required|in:accept,decline']);

        if ($request->action === 'accept') {
            $team = $invite->team;
            
            // Check if invited user is registered for the event
            $isRegistered = \App\Models\EventRegistration::where('event_id', $team->event_id)
                ->where('user_id', Auth::id())
                ->exists();

            if (!$isRegistered) {
                return redirect()->route('front.events.show', $team->event->slug)
                    ->with('error', 'You must register for this event before you can accept the team invitation.');
            }

            $teamSizeLimit = $team->event->team_size;
            
            if ($teamSizeLimit && $team->members()->where('status', 'joined')->count() >= $teamSizeLimit) {
                return back()->with('error', 'Cannot accept invite. The team is already full.');
            }

            $team->members()->create([
                'user_id' => Auth::id(),
                'status' => 'joined'
            ]);
            $invite->update(['status' => 'approved']);
            
            return back()->with('success', 'You have joined the team!');
        } else {
            $invite->update(['status' => 'rejected']);
            return back()->with('success', 'You have declined the invitation.');
        }
    }

    public function respondRequest(TeamJoinRequest $joinRequest, Request $request)
    {
        if ($joinRequest->team->leader_id !== Auth::id() || $joinRequest->type !== 'request') {
            abort(403);
        }

        $request->validate(['action' => 'required|in:accept,decline']);

        if ($request->action === 'accept') {
            $team = $joinRequest->team;
            $teamSizeLimit = $team->event->team_size;
            
            if ($teamSizeLimit && $team->members()->where('status', 'joined')->count() >= $teamSizeLimit) {
                return back()->with('error', 'Cannot accept request. The team is already full.');
            }

            $team->members()->create([
                'user_id' => $joinRequest->user_id,
                'status' => 'joined'
            ]);
            $joinRequest->update(['status' => 'approved']);
            
            return back()->with('success', 'Request accepted. User added to team!');
        } else {
            $joinRequest->update(['status' => 'rejected']);
            return back()->with('success', 'Request declined.');
        }
    }
    public function show(Team $team)
    {
        $team->load(['event', 'leader', 'members.user']);
        
        $isMember = $team->members()->where('user_id', Auth::id())->exists();
        $isLeader = $team->leader_id === Auth::id();

        // Even non-members might be allowed to view the team, but let's restrict it to members for privacy
        // Or actually, anyone might want to see who's in the team before joining.
        // Let's allow any authenticated user to view the team info.

        $pendingRequests = collect();
        if ($isLeader) {
            $pendingRequests = $team->joinRequests()->where('type', 'request')->where('status', 'pending')->with('user')->get();
        }

        return view('user.teams.show', compact('team', 'isMember', 'isLeader', 'pendingRequests'));
    }

    public function destroy(Team $team)
    {
        if ($team->leader_id !== Auth::id()) {
            abort(403);
        }

        // Deleting the team will cascade to members and requests based on foreign keys (or we can just delete it)
        $team->delete();

        return redirect()->route('dashboard')->with('success', 'Team disbanded successfully.');
    }

    public function kickMember(Team $team, \App\Models\User $user)
    {
        if ($team->leader_id !== Auth::id()) {
            abort(403);
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot kick yourself.');
        }

        $team->members()->where('user_id', $user->id)->delete();

        return back()->with('success', 'Member kicked successfully.');
    }

    public function leave(Team $team)
    {
        if ($team->leader_id === Auth::id()) {
            return back()->with('error', 'As a leader, you cannot leave the team. You must disband (delete) the team instead.');
        }

        $isMember = $team->members()->where('user_id', Auth::id())->exists();
        
        if (!$isMember) {
            return back()->with('error', 'You are not a member of this team.');
        }

        $team->members()->where('user_id', Auth::id())->delete();

        return redirect()->route('dashboard')->with('success', 'You have left the team.');
    }
}
