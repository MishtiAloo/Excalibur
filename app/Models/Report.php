<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $primaryKey = 'report_id';
    protected $fillable = [
        'case_id','search_group_id','user_id','report_type','description','location_lat','location_lng','sighted_person','reported_at','status'
    ];

    public function caseFile()
    {
        return $this->belongsTo(CaseFile::class, 'case_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function searchGroup()
    {
        return $this->belongsTo(SearchGroup::class, 'search_group_id', 'group_id');
    }

    public function media()
    {
        return $this->hasMany(MediaReport::class, 'report_id');
    }

}
