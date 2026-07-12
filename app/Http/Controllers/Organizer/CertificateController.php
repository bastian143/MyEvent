<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Certificate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index(Event $event)
    {
        if ($event->creator_id !== Auth::id()) abort(403);
        
        $participants = $event->registrations()->with('user')->get();
        return view('organizer.certificates.index', compact('event', 'participants'));
    }

    public function store(Request $request, Event $event)
    {
        if ($event->creator_id !== Auth::id()) abort(403);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'certificate_file' => 'required|file|mimes:pdf,jpg,png|max:5120',
        ]);

        $filePath = $request->file('certificate_file')->store('certificates', 'public');

        $certificate = Certificate::updateOrCreate(
            ['event_id' => $event->id, 'user_id' => $request->user_id],
            [
                'file_path' => $filePath,
                'certificate_number' => 'CERT-' . strtoupper(uniqid()),
            ]
        );

        $user = User::find($request->user_id);
        if ($user) {
            $user->notify(new \App\Notifications\ActivityNotification(
                "Your certificate for event '{$event->title}' is now available.",
                route('dashboard')
            ));
        }

        return back()->with('success', 'Certificate uploaded and issued successfully.');
    }
}
