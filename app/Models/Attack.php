<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attack extends Model
{
    use HasFactory;

    protected $primaryKey = 'report_id';
    public $incrementing = false;
    protected $keyType = 'int';
    protected $fillable = ['report_id','attack_type','victims_count','attacker'];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
