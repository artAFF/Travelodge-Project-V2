<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ehcm_net extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'download',
        'upload',
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
