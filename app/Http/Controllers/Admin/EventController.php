<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('creator', 'category')->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function approve(Event $event)
    {
        $event->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $event->creator->notify(new \App\Notifications\ActivityNotification(
            "Your event '{$event->title}' has been approved!",
            route('organizer.events.index')
        ));

        return back()->with('success', 'Event approved successfully.');
    }

    public function reject(Request $request, Event $event)
    {
        $request->validate(['rejection_reason' => 'required|string']);

        $event->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $event->creator->notify(new \App\Notifications\ActivityNotification(
            "Your event '{$event->title}' has been rejected. Reason: {$request->rejection_reason}",
            route('organizer.events.index')
        ));

        return back()->with('success', 'Event rejected successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Event deleted successfully.');
    }
}
