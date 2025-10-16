<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hazard extends Model
{
    use HasFactory;

    protected $primaryKey = 'report_id';
    public $incrementing = false;
    protected $keyType = 'int';
    protected $fillable = ['report_id','hazard_type','severity'];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
