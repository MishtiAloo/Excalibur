<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $primaryKey = 'alert_id';
    protected $fillable = ['case_id','alert_type','status','approved_by','expires_at','message'];

    public function caseFile()
    {
        return $this->belongsTo(CaseFile::class, 'case_id');
    }

    public function approvedByOfficer()
    {
        return $this->belongsTo(Officer::class, 'approved_by', 'officer_id');
    }
}
