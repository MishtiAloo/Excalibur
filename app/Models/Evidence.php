<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    use HasFactory;

    protected $primaryKey = 'report_id';
    public $incrementing = false;
    protected $keyType = 'int';
    protected $fillable = ['report_id','received','received_by'];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function receivedBy()
    {
        return $this->belongsTo(Officer::class, 'received_by', 'officer_id');
    }
}
