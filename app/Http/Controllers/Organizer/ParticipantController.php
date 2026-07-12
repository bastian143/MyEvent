<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function index(Event $event)
    {
        if ($event->creator_id !== auth()->id()) abort(403);

        $registrations = $event->registrations()->with(['user', 'answers.field'])->latest()->get();
        return view('organizer.events.registrations.index', compact('event', 'registrations'));
    }

    public function updateStatus(Request $request, Event $event, EventRegistration $registration)
    {
        if ($event->creator_id !== auth()->id() || $registration->event_id !== $event->id) abort(403);

        $request->validate([
            'status' => 'required|in:approved,rejected,pending'
        ]);

        $registration->update(['status' => $request->status]);

        return back()->with('success', 'Participant status updated successfully.');
    }
}
