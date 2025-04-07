<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uncm_switch extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'ups_battery',
        'ups_time',
        'ups_temp',
        'created_by',
    ];

    public function location()
    {
        return $this->belongsTo(location::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
