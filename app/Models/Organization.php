<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['unique_code', 'name'];

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
}
