<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaReport extends Model
{
    use HasFactory;

    protected $primaryKey = 'media_id';
    protected $fillable = ['report_id','uploaded_by','url','description'];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
