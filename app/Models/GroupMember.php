<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;

    protected $table = 'group_members';
    public $incrementing = false; // composite key
    protected $primaryKey = null; // Eloquent doesn't support composite keys; use manual queries when needed
    protected $keyType = 'int';

    protected $fillable = ['group_id', 'volunteer_id'];

    public function group()
    {
        return $this->belongsTo(SearchGroup::class, 'group_id', 'group_id');
    }

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class, 'volunteer_id', 'volunteer_id');
    }
}
