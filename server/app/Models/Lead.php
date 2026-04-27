<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'source',      // website, manual, campaign
        'status',      // new, contacted, qualified, converted
        'user_id',     // owner (agent)
        'assigned_to', // optional reassignment (TL/agent)
        'department',  // credit, AR, AP (optional)
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Lead owner (who created it)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Assigned user (can be different from creator)
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes (VERY useful)
    |--------------------------------------------------------------------------
    */

    // Leads of current user
    public function scopeMine($query)
    {
        return $query->where('user_id', auth()->id());
    }

    // Leads for a team (manager/TL)
    public function scopeForTeam($query, $user)
    {
        $ids = $this->getTeamIds($user);
        return $query->whereIn('user_id', $ids);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper (Hierarchy)
    |--------------------------------------------------------------------------
    */

    private function getTeamIds($user)
    {
        $ids = [$user->id];

        foreach ($user->subordinates as $sub) {
            $ids = array_merge($ids, $this->getTeamIds($sub));
        }

        return $ids;
    }
}
