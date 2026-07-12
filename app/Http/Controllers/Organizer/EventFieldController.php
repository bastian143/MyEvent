<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventFormField;
use Illuminate\Http\Request;

class EventFieldController extends Controller
{
    public function index(Event $event)
    {
        // Ensure user owns this event
        if ($event->creator_id !== auth()->id()) abort(403);
        
        $fields = $event->formFields;
        return view('organizer.events.fields.index', compact('event', 'fields'));
    }

    public function store(Request $request, Event $event)
    {
        if ($event->creator_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:text,email,textarea,select,file',
            'options' => 'nullable|string', // Comma separated for simplicity in UI
            'is_required' => 'nullable|boolean',
        ]);

        $optionsArray = null;
        if ($validated['type'] === 'select' && !empty($validated['options'])) {
            $optionsArray = array_map('trim', explode(',', $validated['options']));
        }

        EventFormField::create([
            'event_id' => $event->id,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'options' => $optionsArray,
            'is_required' => $request->has('is_required'),
            'order' => $event->formFields()->count() + 1,
        ]);

        return back()->with('success', 'Form field added successfully.');
    }

    public function destroy(Event $event, EventFormField $field)
    {
        if ($event->creator_id !== auth()->id() || $field->event_id !== $event->id) abort(403);
        $field->delete();
        return back()->with('success', 'Form field deleted successfully.');
    }
}
