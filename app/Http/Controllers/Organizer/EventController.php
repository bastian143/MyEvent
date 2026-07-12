<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Auth::user()->createdEvents()->latest()->paginate(10);
        return view('organizer.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('organizer.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'event_type' => 'required|in:online,offline,hybrid',
            'registration_type' => 'required|in:individual,team',
            'team_size' => 'nullable|integer|min:2',
            'quota' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'location' => 'nullable|string',
            'registration_deadline' => 'required|date|before_or_equal:start_date',
            'contact_person' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:50',
            'poster' => 'required|image|max:2048'
        ]);

        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $validated['quota'] = $validated['quota'] ?? 0;
        $validated['price'] = $validated['price'] ?? 0;

        $validated['creator_id'] = Auth::id();
        $validated['slug'] = \Str::slug($validated['title']) . '-' . uniqid();
        $validated['status'] = 'pending';

        $event = Event::create($validated);

        if ($request->has('form_fields')) {
            foreach ($request->form_fields as $field) {
                if (isset($field['label']) && !empty($field['label'])) {
                    $event->formFields()->create([
                        'name' => $field['label'],
                        'type' => $field['type'] ?? 'text',
                        'is_required' => isset($field['is_required']) ? filter_var($field['is_required'], FILTER_VALIDATE_BOOLEAN) : true,
                    ]);
                }
            }
        }

        return redirect()->route('organizer.events.index')->with('success', 'Event created successfully and is pending approval.');
    }
}
