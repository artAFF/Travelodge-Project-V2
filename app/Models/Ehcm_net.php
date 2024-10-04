<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ehcm_net extends Model
{
    use HasFactory;

    public function location()
    {
        return $this->belongsTo(location::class);
    }
}
