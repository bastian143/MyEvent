<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistrationAnswer extends Model
{
    protected $fillable = [
        'event_registration_id',
        'event_form_field_id',
        'value',
    ];

    public function registration()
    {
        return $this->belongsTo(EventRegistration::class, 'event_registration_id');
    }

    public function field()
    {
        return $this->belongsTo(EventFormField::class, 'event_form_field_id');
    }
}
