<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
    use HasFactory;

    protected $primaryKey = 'report_id';
    public $incrementing = false;
    protected $keyType = 'int';
    protected $fillable = ['report_id','credibility_score','verified_by'];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Officer::class, 'verified_by', 'officer_id');
    }
}
