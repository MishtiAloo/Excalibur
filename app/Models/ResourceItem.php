<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceItem extends Model
{
    use HasFactory;

    protected $table = 'resources';
    protected $primaryKey = 'resource_id';
    protected $fillable = ['name','stored_lat','stored_lng','condition','availability','count','availableCount'];

    public function bookings()
    {
        return $this->hasMany(ResourceBooking::class, 'resource_id');
    }
}
