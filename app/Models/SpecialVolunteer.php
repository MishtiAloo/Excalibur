<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialVolunteer extends Model
{
    use HasFactory;

    protected $primaryKey = 'special_volunteer_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = ['special_volunteer_id','terrain_type','verified_by_officer'];

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class, 'special_volunteer_id', 'volunteer_id');
    }

    public function verifiedByOfficer()
    {
        return $this->belongsTo(Officer::class, 'verified_by_officer', 'officer_id');
    }
}
