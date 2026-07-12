<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'creator_id',
        'title',
        'slug',
        'description',
        'poster',
        'location',
        'event_type',
        'registration_type',
        'team_size',
        'registration_deadline',
        'start_date',
        'end_date',
        'quota',
        'price',
        'contact_person',
        'contact_phone',
        'status',
        'rejection_reason',
        'approved_by',
        'approved_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function formFields()
    {
        return $this->hasMany(EventFormField::class)->orderBy('order');
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
}
