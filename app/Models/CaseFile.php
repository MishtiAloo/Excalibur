<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseFile extends Model
{
    use HasFactory;

    protected $table = 'cases';
    protected $primaryKey = 'case_id';
    protected $fillable = [
        'created_by','case_type','title','description','coverage_lat','coverage_lng','coverage_radius','status','urgency'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function searchGroups()
    {
        return $this->hasMany(SearchGroup::class, 'case_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'case_id');
    }


    public function alerts()
    {
        return $this->hasMany(Alert::class, 'case_id');
    }
}
