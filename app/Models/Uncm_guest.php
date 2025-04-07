<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uncm_guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'room_no',
        'download',
        'upload',
        'ch_no',
        'ch_name',
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
