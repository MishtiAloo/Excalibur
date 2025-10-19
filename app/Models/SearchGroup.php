<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchGroup extends Model
{
    use HasFactory;

    protected $table = 'search_groups';
    protected $primaryKey = 'group_id';
    protected $fillable = [
        'case_id','leader_id','type','intensity','status',
        'start_time','duration','report_back_time','max_volunteers','available_volunteer_slots',
        'instruction','allocated_lat','allocated_lng','radius'
    ];

    public function caseFile()
    {
        return $this->belongsTo(CaseFile::class, 'case_id');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function volunteers()
    {
        return $this->belongsToMany(Volunteer::class, 'group_members', 'group_id', 'volunteer_id');
    }

    public function resourceBookings()
    {
        return $this->hasMany(ResourceBooking::class, 'group_id');
    }
}
