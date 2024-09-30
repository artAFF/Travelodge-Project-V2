<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = ['hotel_full_name', 'hotel_code'];

    public function travelodges()
    {
        return $this->hasMany(Travelodge::class);
    }
}
