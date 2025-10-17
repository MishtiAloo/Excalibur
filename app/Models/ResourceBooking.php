<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceBooking extends Model
{
    use HasFactory;

    protected $primaryKey = 'booking_id';
    protected $fillable = ['resource_id','group_id','checked_out_by','check_out_time','check_in_time'];

    public function resource()
    {
        return $this->belongsTo(ResourceItem::class, 'resource_id');
    }

    public function group()
    {
        return $this->belongsTo(SearchGroup::class, 'group_id');
    }

    public function checkedOutBy()
    {
        return $this->belongsTo(User::class, 'checked_out_by');
    }
}
