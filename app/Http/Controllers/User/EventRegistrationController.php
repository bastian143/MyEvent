<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventRegistrationController extends Controller
{
    public function create(Event $event)
    {
        if ($event->status !== 'approved') {
            return back()->with('error', 'Event is not open for registration.');
        }

        $existing = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($existing) {
            return redirect()->route('front.events.show', $event->slug)->with('error', 'You are already registered for this event.');
        }

        return view('user.events.register', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        if ($event->status !== 'approved') abort(403);

        // Validation for dynamic fields
        $rules = [];
        foreach ($event->formFields as $field) {
            $rule = $field->is_required ? ['required'] : ['nullable'];
            if ($field->type === 'file') {
                $rule[] = 'file';
                $rule[] = 'max:5120';
                $rules['files.' . $field->id] = $rule;
            } else {
                $rules['answers.' . $field->id] = $rule;
            }
        }
        $request->validate($rules);

        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'status' => 'pending' // Dynamic forms default to pending for organizer screening
        ]);

        foreach ($event->formFields as $field) {
            $value = null;
            if ($field->type === 'file' && $request->hasFile('files.' . $field->id)) {
                $value = $request->file('files.' . $field->id)->store('registration_files', 'public');
            } elseif (isset($request->answers[$field->id])) {
                $value = $request->answers[$field->id];
            }

            if ($value !== null) {
                \App\Models\EventRegistrationAnswer::create([
                    'event_registration_id' => $registration->id,
                    'event_form_field_id' => $field->id,
                    'value' => $value,
                ]);
            }
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Registered for event: ' . $event->title
        ]);

        // Send notification
        Auth::user()->notify(new \App\Notifications\ActivityNotification(
            "You have successfully registered for '{$event->title}'.",
            route('dashboard')
        ));

        return redirect()->route('dashboard')->with('success', 'Successfully registered for the event! Awaiting organizer approval.');
    }
}
