<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventFormField extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'type',
        'options',
        'is_required',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
