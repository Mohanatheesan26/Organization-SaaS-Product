<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['organization_id', 'serial_number', 'name', 'ipv4_address'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
