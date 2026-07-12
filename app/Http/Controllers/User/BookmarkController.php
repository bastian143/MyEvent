<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function toggle(Event $event)
    {
        $bookmark = Bookmark::where('user_id', Auth::id())
                            ->where('event_id', $event->id)
                            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return back()->with('success', 'Event removed from saved list.');
        } else {
            Bookmark::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
            ]);
            return back()->with('success', 'Event saved successfully.');
        }
    }
}
