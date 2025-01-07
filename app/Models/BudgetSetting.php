<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetSetting extends Model
{
    use HasFactory;

    protected $fillable = ['hotel_id', 'month', 'budget_amount'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
