<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruction extends Model
{
    use HasFactory;

    protected $primaryKey = 'instruction_id';
    protected $fillable = ['group_id','case_id','officer_id','details','issued_at'];

    public function group()
    {
        return $this->belongsTo(SearchGroup::class, 'group_id');
    }

    public function caseFile()
    {
        return $this->belongsTo(CaseFile::class, 'case_id');
    }

    public function officer()
    {
        return $this->belongsTo(Officer::class, 'officer_id', 'officer_id');
    }
}
