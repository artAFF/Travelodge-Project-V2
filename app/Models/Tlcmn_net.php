<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tlcmn_net extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'download',
        'upload'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
