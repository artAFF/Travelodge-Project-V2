<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'type'];

    public function tlcmnGuest()
    {
        return $this->hasMany(Tlcmn_guest::class);
    }

    public function ehcmGuest()
    {
        return $this->hasMany(Ehcm_guest::class);
    }

    public function uncmGuest()
    {
        return $this->hasMany(Uncm_guest::class);
    }
    public function tlcmnNets()
    {
        return $this->hasMany(Tlcmn_net::class);
    }

    public function ehcmNets()
    {
        return $this->hasMany(Ehcm_net::class);
    }

    public function uncmNets()
    {
        return $this->hasMany(Uncm_net::class);
    }

    public function tlcmnSwitchs()
    {
        return $this->hasMany(Tlcmn_switch::class);
    }

    public function ehcmSwitchs()
    {
        return $this->hasMany(Ehcm_switch::class);
    }

    public function uncmSwitchs()
    {
        return $this->hasMany(Uncm_switch::class);
    }
}
