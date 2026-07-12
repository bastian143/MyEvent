<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['event_id', 'leader_id', 'name', 'status'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function joinRequests()
    {
        return $this->hasMany(TeamJoinRequest::class);
    }
}
