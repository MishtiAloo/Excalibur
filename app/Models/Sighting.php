<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sighting extends Model
{
    use HasFactory;

    protected $primaryKey = 'report_id';
    public $incrementing = false;
    protected $keyType = 'int';
    protected $fillable = ['report_id','sighted_person','time_seen'];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
