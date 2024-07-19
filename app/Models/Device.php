<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['location_id', 'unique_number', 'type', 'image', 'date_created', 'status'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
