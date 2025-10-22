<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaCase extends Model
{
    use HasFactory;

    protected $table = 'media_cases';
    protected $primaryKey = 'media_id';
    protected $fillable = ['case_id','uploaded_by','url','description'];

    public function caseFile()
    {
        return $this->belongsTo(CaseFile::class, 'case_id', 'case_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
