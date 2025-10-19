<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    protected $primaryKey = 'volunteer_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['volunteer_id','vetting_status','availability'];

    public function user()
    {
        return $this->belongsTo(User::class, 'volunteer_id', 'id');
    }

    public function specialVolunteer()
    {
        return $this->hasOne(SpecialVolunteer::class, 'special_volunteer_id', 'volunteer_id');
    }

    public function searchGroups()
    {
        return $this->belongsToMany(SearchGroup::class, 'group_members', 'volunteer_id', 'group_id')->withTimestamps();
    }
}
