<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $primaryKey = 'report_id';
    protected $fillable = [
        'case_id','user_id','report_type','description','location_lat','location_lng','timestamp','status'
    ];

    public function caseFile()
    {
        return $this->belongsTo(CaseFile::class, 'case_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->hasMany(MediaReport::class, 'report_id');
    }

    public function tip()
    {
        return $this->hasOne(Tip::class, 'report_id');
    }

    public function evidence()
    {
        return $this->hasOne(Evidence::class, 'report_id');
    }

    public function sighting()
    {
        return $this->hasOne(Sighting::class, 'report_id');
    }

    public function hazard()
    {
        return $this->hasOne(Hazard::class, 'report_id');
    }

    public function attack()
    {
        return $this->hasOne(Attack::class, 'report_id');
    }
}
