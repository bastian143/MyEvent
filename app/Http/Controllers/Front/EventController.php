<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('category')->where('status', 'approved');

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $events = $query->latest()->paginate(9);
        $categories = Category::all();

        return view('welcome', compact('events', 'categories'));
    }

    public function show(Event $event)
    {
        if ($event->status !== 'approved') {
            abort(404);
        }
        $event->load('category', 'creator');
        
        $pendingInvites = collect();
        if (Auth::check()) {
            $pendingInvites = \App\Models\TeamJoinRequest::where('user_id', Auth::id())
                ->where('type', 'invite')
                ->where('status', 'pending')
                ->whereHas('team', function($q) use ($event) {
                    $q->where('event_id', $event->id);
                })
                ->with('team.leader')
                ->get();
        }

        return view('front.events.show', compact('event', 'pendingInvites'));
    }
}
