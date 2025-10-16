<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    use HasFactory;

    protected $primaryKey = 'officer_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'officer_id', 'badge_no', 'department', 'rank'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'officer_id', 'id');
    }

    public function instructions()
    {
        return $this->hasMany(Instruction::class, 'officer_id');
    }

    public function verifiedSpecialVolunteers()
    {
        return $this->hasMany(SpecialVolunteer::class, 'verified_by_officer');
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class, 'approved_by');
    }
}
