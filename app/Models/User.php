<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nid',
        'phone',
        'role',
        'status',
        'info_credibility',
        'responsiveness',
        'permanent_lat',
        'permanent_lng',
        'current_lat',
        'current_lng',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    // Relationships
    public function officer()
    {
        return $this->hasOne(Officer::class, 'officer_id', 'id');
    }

    public function volunteer()
    {
        return $this->hasOne(Volunteer::class, 'volunteer_id', 'id');
    }

    public function cases()
    {
        return $this->hasMany(CaseFile::class, 'created_by');
    }

    public function searchGroupsLed()
    {
        return $this->hasMany(SearchGroup::class, 'leader_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function skills()
    {
    return $this->belongsToMany(
        Skill::class,
        'user_skills',      // pivot table
        'user_id',          // FK on pivot referencing users
        'skill_id',         // FK on pivot referencing skills
        'id',               // local key on users
        'skill_id'          // related key on skills
        )
            ->withPivot(['level', 'verified'])
            ->withTimestamps();
    }

    public function resourceBookings()
    {
        return $this->hasMany(ResourceBooking::class, 'checked_out_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
